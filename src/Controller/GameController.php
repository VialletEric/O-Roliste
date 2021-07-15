<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameMessageRepository;
use App\Repository\GameRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game", name="game_")
 */
class GameController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(GameRepository $gameRepository): Response
    {
        return $this->render('game/browse.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    public function autorisationCheck(Game $game)
    {
        $currentUser = $this->getUser();

        return $game->getGuests()->contains($currentUser) || $game->getCreator() == $currentUser;
    }

    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"})
     */
    public function read(GameMessageRepository $gameMessageRepository, Game $game): Response
    {
        //take all the message per id
        $gameMessages = $gameMessageRepository->findByGameId($game->getId());
        $idGame = $game->getId();
        $autorisation = $this->autorisationCheck($game);
        return $this->render('game/read.html.twig', [
            'idGame' => $idGame,
            'gameMessages' => $gameMessages,
            'game' => $game,
            'autorisation' => $autorisation,
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, ImageUploader $imageUploader): Response
    {
        $game = new Game();

        $this->denyAccessUnlessGranted('ROLE_USER', $game);

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
       
        //set current user as a game creator
        $game->setCreator($this->getUser());
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //upload illustration image
            $newIllustrationPicture = $imageUploader->upload($form, 'image');
            if ($newIllustrationPicture !== null) {
                $game->setImage($newIllustrationPicture);
            }

            //add default avatar image if the field is empty
            if ($game->getImage() === null) {
                $game->setImage("default/game-default.svg");
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            return $this->redirectToRoute('game_read',['id'=>$game->getId()]);
        }

        return $this->render('game/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id"="\d+"})
     */
    public function edit(Game $game, Request $request, ImageUploader $imageUploader): Response
    {

        $this->denyAccessUnlessGranted('GAME_EDIT', $game);

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        //upload illustration image
       $newIllustrationPicture = $imageUploader->upload($form, 'image');
        if ($newIllustrationPicture !== null) {
            $game->setImage($newIllustrationPicture);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $game->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('game_read',['id'=>$game->getId()]);
        }

        return $this->render('game/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id"="\d+"})
     */
    public function delete(Game $game, Request $request)
    {
        $this->denyAccessUnlessGranted('GAME_EDIT',$game);

        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();

            $this->addFlash('success', 'Partie supprimée');
        }
        return $this->redirectToRoute('game_browse');
    }
}
