<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameMessage;
use App\Form\GameMessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game/{id}/message", name="game_message_", requirements={"id": "\d+"})
 *  */
class GameMessageController extends AbstractController
{

    public function form(int $gameId)
    {
        $gameMessage = new GameMessage();

        $this->denyAccessUnlessGranted('ROLE_USER', $gameMessage);

        $form = $this->createForm(GameMessageType::class, $gameMessage, [
            'action' => $this->generateUrl('game_message_new', ['id' => $gameId])
        ]);

        return $this->render('game_message/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/new", name="new")
     */
    public function new(Request $request,Game $game): Response
    {
        $gameMessage = new GameMessage();

        $this->denyAccessUnlessGranted('ROLE_USER', $gameMessage);

        $form = $this->createForm(GameMessageType::class, $gameMessage);
        $form->handleRequest($request);

        //set current User into user.id in entity GameMessage
        $gameMessage->setUser($this->getUser());

        //set current Game into game.id in entity GameMessage
        $gameMessage->setGame($game);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gameMessage);
            $entityManager->flush();

            $this->addFlash('success', 'message envoyé.');

            $id=$gameMessage->getGame()->getId();
            return $this->redirectToRoute('game_read',['id'=>$id]);
        }

        return $this->render('game_message/new.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }

    /**
     * @Route("/edit/{idMessage}", name="edit", requirements={"idMessage": "\d+"})
     * @ParamConverter("gameMessage", options={"id"="idMessage"})
     */     
    public function edit(Request $request, GameMessage $gameMessage): Response
    {
        $this->denyAccessUnlessGranted('MESSAGE_EDIT',$gameMessage);

        $form = $this->createForm(GameMessageType::class, $gameMessage);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $gameMessage->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();            
            
            $this->addFlash('success', 'Message edité.');    

            $id=$gameMessage->getGame()->getId();
            return $this->redirectToRoute('game_read',['id'=>$id]);
        }

        return $this->render('game_message/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{idMessage}", name="delete", requirements={"idMessage": "\d+"})
     * @ParamConverter("gameMessage", options={"id"="idMessage"})
     */
    public function delete(Request $request, GameMessage $gameMessage): Response
    {
        $this->denyAccessUnlessGranted('MESSAGE_EDIT',$gameMessage);

        if ($this->isCsrfTokenValid('delete'.$gameMessage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gameMessage);
            $entityManager->flush();

            $this->addFlash('success', 'Message supprimé.');    
        }
        $id=$gameMessage->getGame()->getId();
        return $this->redirectToRoute('game_read',['id'=>$id]);
    }
}
