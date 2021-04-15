<?php

namespace App\Controller\Admin;

use App\Form\ValidatorType;
use App\Repository\ResourceRepository;
use App\Utils\State;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ressources", name="admin_resource_")
 */
class ResourceController extends AbstractController
{
    private ResourceRepository $resourceRepository;

    public function __construct(ResourceRepository $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @Route("/en-attente", name="waiting", priority=5)
     */
    public function waitingResource(PaginatorInterface $paginator, Request $request): Response
    {
        return $this->render('admin/resource/index.html.twig', [
            'resources' => $paginator->paginate(
                $this->resourceRepository->getResourceByState(State::WAITING),
                $request->query->get('page', 1),
                $this->getParameter('pagination.admin.resources')
            )
        ]);
    }

    /**
     * @Route("/en-ligne", name="inline", priority=5)
     */
    public function inlineResource(PaginatorInterface $paginator, Request $request): Response
    {
        return $this->render('admin/resource/index.html.twig', [
            'resources' => $paginator->paginate(
                $this->resourceRepository->getResourceByState(State::VALIDATED),
                $request->query->get('page', 1),
                $this->getParameter('pagination.admin.resources')
            )
        ]);
    }

    /**
     * @Route("/refuser", name="denied", priority=5)
     */
    public function deniedResource(PaginatorInterface $paginator, Request $request): Response
    {
        return $this->render('admin/resource/index.html.twig', [
            'resources' => $paginator->paginate(
                $this->resourceRepository->getResourceByState(State::REFUSED),
                $request->query->get('page', 1),
                $this->getParameter('pagination.admin.resources')
            )
        ]);
    }

    /**
     * @Route("/{id}", name="show", priority=2)
     */
    public function showWaitingResource(\App\Entity\Resource $resource, Request $request): Response
    {
        $form = $this->createForm(ValidatorType::class, $resource);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resource);
            $entityManager->flush();
            switch ($resource->getValidation()) {
                case State::VALIDATED:
                    $this->addFlash('success','La Ressource a bien été accepté');
                    break;
                case State::REFUSED:
                    $this->addFlash('error','La Ressource a bien été refusé');
                    break;
                default:
                    $this->addFlash('warning','La Ressource est en attente');
            }
        }
        return $this->render('admin/resource/show.html.twig', [
            'resource' => $resource,
            'form'     => $form->createView()
        ]);
    }

}
