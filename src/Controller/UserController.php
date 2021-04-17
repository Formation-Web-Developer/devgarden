<?php

namespace App\Controller;

use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/profil", name="profile", priority=5)
     */
    public function profile(PaginatorInterface $paginator, Request $request):Response
    {
        return $this->showUser($paginator, $this->getUser(), $request);
    }

    /**
     * @Route("/{name}", name="show", priority=3)
     */
    public function user(PaginatorInterface $paginator, User $user, Request $request):Response
    {
        return $this->showUser($paginator, $user, $request);
    }

    private function showUser(PaginatorInterface $paginator, User $user, Request $request): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'self' => $this->getUser() && $this->getUser()->getId() === $user->getId(),
            'resources' => $paginator->paginate(
                $user->getResources(),
                $request->query->get('page', 1),
                $this->getParameter('user.resources.limit')
            )
        ]);
    }
}
