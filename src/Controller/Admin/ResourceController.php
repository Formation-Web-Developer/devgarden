<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\ValidatorType;
use App\Repository\CategoryRepository;
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
    public function showWaitingResource(CategoryRepository $repository,\App\Entity\Resource $resource, Request $request): Response
    {
        $form = $this->createForm(ValidatorType::class, $resource);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            if (!$request->request->has('categories')) {
                $this->addFlash('error', 'La catégorie n\'a pas été précisé.');
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $category_id = $request->request->get('categories');
                $category = $repository->find($category_id);

                if ($category === null) {
                    $category = (new Category())
                        ->setName($category_id);
                    $entityManager->persist($category);
                    $resource->setValidation(State::WAITING);
                    $this->addFlash('success', 'La catégorie a bien été créé. Veuillez remplir les champs avant de valider la ressource.');

                    $resource->setCategory($category);

                    $entityManager->persist($resource);
                    $entityManager->flush();

                    return $this->redirectToRoute('admin_category_edit', [
                        'id' => $category->getId()
                    ]);
                }

                if (empty($category->getSlug()) && $resource->getValidation() === State::VALIDATED) {
                    $resource->setValidation(State::WAITING);
                    $this->addFlash('warning', 'Vous devez d\'abord enregistrer la catégorie avant d\'accepter la ressource.');
                }
                $resource->setCategory($category);
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
        }
        return $this->render('admin/resource/show.html.twig', [
            'resource' => $resource,
            'form'     => $form->createView()
        ]);
    }

}
