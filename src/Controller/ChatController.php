<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Listing;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/chats')]
class ChatController extends AbstractController
{
    #[Route('', name: 'app_chats')]
    public function index(ConversationRepository $conversationRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $theme = $user->getTheme();

        $conversations = $conversationRepo->findForUser($user);

        return $this->render('themes/' . $theme->value . '/chat/index.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/{id}', name: 'app_chat_show', requirements: ['id' => '\d+'])]
    public function show(Conversation $conversation): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $theme = $user->getTheme();

        if (!$conversation->getParticipants()->contains($user)) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('themes/' . $theme->value . '/chat/show.html.twig', [
            'conversation' => $conversation,
        ]);
    }

    #[Route('/{id}/messages', name: 'app_chat_message', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function sendMessage(
        Conversation $conversation,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (!$conversation->getParticipants()->contains($user)) {
            throw $this->createAccessDeniedException();
        }

        $content = trim((string) $request->request->get('content'));
        if ($content === '') {
            return $this->redirectToRoute('app_chat_show', ['id' => $conversation->getId()]);
        }

        $message = new Message();
        $message->setContent($content);
        $message->setSender($user);
        $message->setConversation($conversation);
        $em->persist($message);
        $em->flush();

        if ($request->headers->get('Turbo-Frame')) {
            return $this->render('chat/_message.html.twig', [
                'message' => $message,
                'currentUser' => $user,
            ]);
        }

        return $this->redirectToRoute('app_chat_show', ['id' => $conversation->getId()]);
    }

    #[Route('/listing/{id}/start', name: 'app_chat_start', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function startConversation(
        Listing $listing,
        ConversationRepository $conversationRepo,
        EntityManagerInterface $em,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $seller = $listing->getSeller();

        if ($seller?->getId() === $user->getId()) {
            return $this->redirectToRoute('app_listing_show', ['id' => $listing->getId()]);
        }

        $conversation = $conversationRepo->findBetweenUsersForListing($user, $seller, $listing->getId());

        if (!$conversation) {
            $conversation = new Conversation();
            $conversation->setListing($listing);
            $conversation->addParticipant($user);
            $conversation->addParticipant($seller);
            $em->persist($conversation);
            $em->flush();
        }

        return $this->redirectToRoute('app_chat_show', ['id' => $conversation->getId()]);
    }
}
