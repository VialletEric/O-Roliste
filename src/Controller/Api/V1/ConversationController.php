<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Form\SeachUserType;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/conversation", name="api_v1_conversation_")
 */
class ConversationController extends AbstractController
{

    /**
     * @Route("search", name="search_user", methods={"GET"})
     */
    public function searchUser(Request $request, UserRepository $userRepository)
    {
       $user = new User();
       
       $form = $this->createForm(SeachUserType::class, $user);

       $json = $request->getContent();

       $jsonArray = json_decode($json, true);

       $form->submit($jsonArray);

       if ($form->isValid()) {
           $keyword = $user->getUsername();

           $result = $userRepository->searchUser($keyword);

           return $this->json($result, Response::HTTP_CREATED);
       }
    }
}