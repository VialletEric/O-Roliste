<?php

namespace App\Controller\Api\V1;

use App\Entity\Conversation;
use App\Entity\Game;
use App\Entity\User;
use App\Entity\MessageUser;
use App\Form\MessageUserType;
use App\Repository\MessageUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/v1/message", name="api_v1_message_")
 */
class MessageUserController extends AbstractController
{
    /**
     * @Route("/user", name="user", methods={"GET"})
     */
    public function userName(): Response
    {
        $user = $this->getUser();


        return $this->json($user, Response::HTTP_OK, [], [
            'groups' => 'user_browse'
        ]);
    }


    /**     
     * @Route("/user/conversation/{id}", name="browseUserMessage", methods={"GET"}, requirements={"id": "\d+"})
     *
     * */
    public function browse(MessageUserRepository $messageUserRepository, Conversation $conversation)
    {
        $message = $messageUserRepository->findByConvId($conversation->getId());
        return $this->json($message, Response::HTTP_OK);
    }

    /**     
     * @Route("/user/conversation/{id}", name="addUserMessage", methods={"POST"}, requirements={"id": "\d+"})
     *
     * @return void
     */
    public function add(Request $request, Conversation $conversation)
    {
        $message = new MessageUser();

        $form = $this->createForm(MessageUserType::class, $message, ['csrf_protection' => false]);

        $json = $request->getContent();

        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);
        if ($form->isValid()) {
            $message->setUser($this->getUser());
            $message->setConversation($conversation);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->json('message created', Response::HTTP_OK);
        }
    }
}
