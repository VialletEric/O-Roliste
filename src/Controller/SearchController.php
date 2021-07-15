<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Form\SearchType;
use App\Form\SearchUserType;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/search", name="search_")
 */
class SearchController extends AbstractController
{
    public function searchBar()
    {
        $searchResult = new Game();
        $form = $this->createForm(SearchType::class, $searchResult, [
            'csrf_protection' => false,
            'action' => $this->generateUrl('search_handle')
        ]);
        return $this->render('form/searchForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("", name="handle" )
     *
     * @param Request $request
     */
    public function search(Request $request, GameRepository $gameRepository)
    {
        $formData = $request->query->get('search');
        $tags = $formData['tags'] ?? null;
        
        $category = !$formData['category'] ? null :  intval($formData['category']);
        $searchResult = $gameRepository->search($formData['name'], $category, $tags);
        return $this->render('search/result.html.twig', [
            'formData' => $formData,
            'results' => $searchResult
        ]);
    }

    public function searchUserForm()
    {
        $userSearch = new User();
        $form = $this->createForm(SearchUserType::class, $userSearch, [
            'csrf_protection' => false,
            'action' => $this->generateUrl('search_users')
        ]);
        return $this->render('form/searchFormUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user", name="users") 
     */
    public function searchUserConv(Request $request, UserRepository $userRepository)
    {
        $formData = $request->query->get('search_user');
;
        $result = $userRepository->searchUsers($formData['username']);
        return $this->render('search/result.html.twig',[
            'users' => $result,
        ]);
    }    
}
