<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\Theme;
use App\Repository\CategoryRepository;
use App\Repository\ListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ListingRepository $listingRepo,
        CategoryRepository $categoryRepo,
    ): Response {
        $user = $this->getUser();
        $theme = ($user instanceof User) ? $user->getTheme() : Theme::Olx;

        $listings = $listingRepo->findActiveByTheme($theme, 12);
        $categories = $categoryRepo->findByTheme($theme);

        return $this->render('themes/' . $theme->value . '/home.html.twig', [
            'listings' => $listings,
            'categories' => $categories,
        ]);
    }
}
