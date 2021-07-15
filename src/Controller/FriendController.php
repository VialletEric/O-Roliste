<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/friend", name="friend_")
 */
class FriendController extends AbstractController
{
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"})
     */
    public function read(UserRepository $userRepository, User $user): Response
    {
        $friends = $userRepository->findByUserId($user->getId());
        $idUser=$user->getId();

        return $this->render('friend/read.html.twig', [
            'friends' => $friends,
            'idUser' => $idUser,
            'user' => $user
        ]);
    }

    /**
     * @Route("/add/{id}", name="add", requirements={"id"="\d+"})
     */
    public function add(Request $request, User $user, UserRepository $userRepository)

    {
        $currentUser = $this->getUser();
        $currentUser->addMyFriend($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        
        $id = $currentUser->getId();
        return $this->redirectToRoute('friend_read' ,['id'=>$id]);
    }

     /**
     * @Route("/delete/{id}", name="delete", requirements={"id"="\d+"})
     */
    public function delete(Request $request, User $user): Response
    {
        $currentUser = $this->getUser();

        $this->denyAccessUnlessGranted('FRIEND_DELETE',$currentUser);

        $currentUser->removeMyFriend($user);
        $this->getDoctrine()->getManager()->flush();        
        $id = $currentUser->getId();
        return $this->redirectToRoute('friend_read' ,['id'=>$id]);
    }
}
