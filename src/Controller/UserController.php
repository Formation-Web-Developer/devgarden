<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateurs", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", priority=5)
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/profil", name="profil", priority=5)
     * @IsGranted("ROLE_USER")
     */
    public function profile():Response
    {
        return $this->showUser($this->getUser());
    }

    /**
     * @Route("/{name}", name="show", priority=3)
     */
    public function user(User $user):Response
    {
        return $this->showUser($user);
    }

    private function showUser(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'self' => $this->getUser() && $this->getUser()->getId() === $user->getId()
        ]);
    }
}
