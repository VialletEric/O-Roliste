<?php

namespace App\Controller;

use App\Entity\MessageUser;
use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\MessageUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/conversation/{id}/message", name="user_message_", requirements={"id": "\d+"})
 */
class MessageController extends AbstractController
{

    /**
     * @Route("/new_message", name="newMessage")
     */
    public function newMessage(Request $request, Conversation $conversation)
    {

        $formData = $request->query->get('newMessage');
        $conversationId = $conversation->getId();

        $newMessage = new MessageUser;
        $newMessage->setBody($formData["body"]);
        $newMessage->setConversation($conversationId);
        $newMessage->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($newMessage);
        $em->flush();

        return $this->redirectToRoute('conversation_read', ['id' => $conversationId]);
    }

    /**
     * @Route("/delete/{idMessage}", name="delete", requirements={"idMessage": "\d+"})
     * @ParamConverter("messageUser", options={"id"="idMessage"})
     *
     * @param MessageUser $messageUser
     * @param Request $request
     * @return void
     */
    public function delete(MessageUser $messageUser, Request $request)
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete' . $messageUser->getId(), $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($messageUser);
            $em->flush();

            $convId = $messageUser->getConversation()->getId();
            return $this->redirectToRoute('conversation_read', ['id'=>$convId]);
        }

        // Si le token n'est pas valide, on lance une exception Access Denied
        throw $this->createAccessDeniedException('Le token n\'est pas valide.'); 
    }
    
}
