<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="user_index")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/utilisateurs/profil", name="user_profile", priority=5)
     */
    public function profile():Response
    {
        return $this->showUser($this->getUser());
    }

    private function showUser(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'self' => $this->getUser() && $this->getUser()->getId() === $user->getId()
        ]);
    }

    /**
     * @Route("/utilisateurs/{name}", name="user", priority="3")
     */
    public function user(User $user):Response
    {
        return $this->showUser($user);
    }

}
