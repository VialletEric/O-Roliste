<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\MessageUser;
use App\Entity\User;
use App\Form\MessageUserType;
use App\Repository\ConversationRepository;
use App\Repository\GameRepository;
use App\Repository\MessageUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/conversation", name="conversation_")
 */
class ConversationController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(ConversationRepository $conversationRepository): Response
    {
        $user = $this->getUser()->getId();
        return $this->render('conversation/browse.html.twig', [
            'controller_name' => 'ConversationController',
            'conversations' => $conversationRepository->searchByUserId($user),
            'currentUser' => $this->getUser()
        ]);
    }

    /**
     *@Route("/add/{id}", name="add", requirements={"id": "\d+"})
     */
    public function add(User $user, ConversationRepository $conversationRepository)
    {
        $user1 = $this->getUser();
        $user2 = $user;
        //dd($conversationRepository->searchRedundancy($user1, $user2));

        $conversation = new Conversation;

        $check1 = $conversationRepository->searchRedundancy($user1, $user2);
        $check2 = $conversationRepository->searchRedundancy($user2, $user1);

        if ($check1 != []) {
            $conversation = $check1[0];
        } elseif ($check2 != []) {
            $conversation = $check2[0];
        } else {
            $conversation->setUser1($user1);
            $conversation->setUser2($user2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($conversation);
            $em->flush();
        }

        return $this->redirectToRoute('conversation_read', ['id' => $conversation->getId()]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param Conversation $conversation
     * @return void
     */
    public function delete(Request $request, Conversation $conversation)
    {
        if ($this->isCsrfTokenValid('delete' . $conversation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($conversation);
            $entityManager->flush();
        }
        return $this->redirectToRoute('conversation_browse');
    }

    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"})
     */
    public function read(Conversation $conversation, MessageUserRepository $messageUserRepository)
    {
        $userMessage = $messageUserRepository->findByConvId($conversation->getId());
        return $this->render('conversation/read.html.twig', [
            'idconv' => $conversation->getId(),
            'user' => $this->getUser(),
            'convMessages' => $userMessage,
            'conversation' => $conversation,
        ]);
    }

    /**
     * @Route("/{id}/form", name="form")
     *
     * @return void
     */
    public function newMessageForm(Conversation $conversation)
    {
        $newMessage = new MessageUser;

        $form = $this->createForm(MessageUserType::class, $newMessage, [
            'csrf_protection' => false,
            'action' => $this->generateUrl('user_message_newMessage', ['id' => $conversation->getId()])
        ]);
        return $this->render('form/searchForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
