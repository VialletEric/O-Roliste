<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/", name="main_")
*/
class MainController extends AbstractController
{

    /**
     * @Route("", name="home")
     */  
    public function index(GameRepository $gameRepository, UserRepository $userRepository): Response
    {

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'gamesFive' => $gameRepository->findBy([], ["createdAt"=>"DESC"], 5),
            'usersFive' => $userRepository->findBy([], ["createdAt"=>"DESC"], 5)
        ]);
    }

    /**
     * @Route("whoarewe", name="whoarewe")
     */
    public function whoarewe()
    {
        return $this->render('main/whoarewe.html.twig');
    }

    /**
     * @Route("contactus", name="contactus")
     */
    public function contactus()
    {
        return $this->render('main/contactus.html.twig');
    }

    /**
     * @Route("legal", name="legal")
     */
    public function legal()
    {
        return $this->render('main/legal.html.twig');
    }
}
