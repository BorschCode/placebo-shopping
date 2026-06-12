<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
    ) {}

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        \Symfony\Component\Mailer\MailerInterface $mailer,
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $em->persist($user);
            $em->flush();

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                (string) $user->getId(),
                $user->getEmail(),
            );

            $email = (new \Symfony\Component\Mime\Email())
                ->from($_ENV['MAILER_SENDER'] ?? 'noreply@placebo-shopping.local')
                ->to($user->getEmail())
                ->subject('Підтвердіть вашу електронну адресу')
                ->html($this->renderView('email/verification.html.twig', [
                    'signedUrl' => $signatureComponents->getSignedUrl(),
                    'expiresAt' => $signatureComponents->getExpiresAt(),
                ]));

            $mailer->send($email);

            $this->addFlash('success', 'Листа з підтвердженням надіслано на ' . $user->getEmail());
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', ['form' => $form]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $em): Response
    {
        $id = $request->query->get('id');
        if (!$id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $em->getRepository(User::class)->find((int) $id);
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('app_register');
        }

        $user->setIsVerified(true);
        $em->flush();

        $this->addFlash('success', 'Адресу підтверджено! Тепер ви можете увійти.');
        return $this->redirectToRoute('app_login');
    }
}
