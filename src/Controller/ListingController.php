<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Entity\User;
use App\Enum\Theme;
use App\Repository\CategoryRepository;
use App\Repository\ConversationRepository;
use App\Repository\ListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/listings')]
class ListingController extends AbstractController
{
    #[Route('', name: 'app_listings')]
    public function index(
        Request $request,
        ListingRepository $listingRepo,
        CategoryRepository $categoryRepo,
    ): Response {
        $user = $this->getUser();
        $theme = ($user instanceof User) ? $user->getTheme() : Theme::Olx;

        $categorySlug = $request->query->get('category');
        $category = $categorySlug ? $categoryRepo->findOneBy(['slug' => $categorySlug]) : null;

        $listings = $category
            ? $listingRepo->findActiveByCategory($category)
            : $listingRepo->findActiveByTheme($theme, 40);

        $categories = $categoryRepo->findByTheme($theme);

        return $this->render('themes/' . $theme->value . '/listing/index.html.twig', [
            'listings' => $listings,
            'categories' => $categories,
            'activeCategory' => $category,
        ]);
    }

    #[Route('/{id}', name: 'app_listing_show', requirements: ['id' => '\d+'])]
    public function show(
        Listing $listing,
        ConversationRepository $conversationRepo,
    ): Response {
        $user = $this->getUser();
        $theme = ($user instanceof User) ? $user->getTheme() : Theme::Olx;

        $conversation = null;
        if ($user instanceof User && $listing->getSeller()?->getId() !== $user->getId()) {
            $conversation = $conversationRepo->findBetweenUsersForListing(
                $user,
                $listing->getSeller(),
                $listing->getId(),
            );
        }

        return $this->render('themes/' . $theme->value . '/listing/show.html.twig', [
            'listing' => $listing,
            'conversation' => $conversation,
        ]);
    }
}
