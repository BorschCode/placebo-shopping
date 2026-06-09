<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Enum\Theme;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    public function show(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('profile/show.html.twig', ['user' => $user]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $profile = $user->getProfile();
        if (!$profile) {
            $profile = new Profile();
            $profile->setUser($user);
            $em->persist($profile);
        }

        $form = $this->createForm(ProfileFormType::class, $profile, [
            'action' => $this->generateUrl('app_profile_edit'),
        ]);
        $form->get('theme')->setData($user->getTheme()->value);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();
                try {
                    $avatarFile->move($this->getParameter('kernel.project_dir') . '/public/uploads/avatars', $newFilename);
                    $profile->setAvatarPath('/uploads/avatars/' . $newFilename);
                } catch (FileException) {
                    $this->addFlash('error', 'Не вдалося завантажити фото.');
                }
            }

            $themeValue = $form->get('theme')->getData();
            $user->setTheme(Theme::from($themeValue));
            $user->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();
            $this->addFlash('success', 'Профіль збережено!');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', ['form' => $form, 'user' => $user]);
    }
}
