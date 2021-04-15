<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/utilisateurs", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", priority=5)
     */
    public function index(UserRepository $repository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/search/{search}", name="search", priority=3)
     */
    public function search(UserRepository $repository, string $search = ''): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repository->searchByName($search),
            'search' => $search
        ]);
    }

    /**
     * @Route("/role/{id}", name="role_update", priority=3, methods={"POST"})
     */
    public function role(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(Role::ADMIN);

        if (
            !$this->isCsrfTokenValid('update-role-'.$user->getId(), $request->request->get('_token'))
                || !Role::has($request->request->get('_role'))
                || !$this->isGranted($request->request->get('_role'))
        ) {
            $this->addFlash('error', 'Une erreur est survenue lors du changement du role.');
            return $this->redirectToRoute('admin_user_index');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user->setRoles([$request->request->get('_role')]);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Le role de '.$user->getName().' a bien été mis à jour !');
        return $this->redirectToRoute('admin_user_index');
    }

    private function getAndCheckFormUpdateRole(array $users): array
    {
        return [];
    }
}
