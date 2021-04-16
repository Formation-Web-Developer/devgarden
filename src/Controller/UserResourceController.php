<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Resource;
use App\Form\ResourceType;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/utilisateurs/profil/ressources", name="user_resource_")
 */
class UserResourceController extends AbstractController
{
    /**
     * @Route("/nouveau", name="new", methods={"GET","POST"}, priority=5)
     */
    public function new(CategoryRepository $repository, Request $request): Response
    {
        $slugify = new Slugify();
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
                }

                $resource->setUser($this->getUser())
                    ->setSlug($slugify->slugify($resource->getName()))
                    ->setCategory($category);

                $entityManager->persist($resource);
                $entityManager->flush();

                return $this->redirectToRoute('user_profile');
            }
        }

        return $this->render('resource/new.html.twig', [
            'resource' => $resource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"}, priority=3)
     */
    public function edit(Request $request, Resource $resource): Response
    {
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('resource/edit.html.twig', [
            'resource' => $resource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete", methods={"POST"}, priority=3)
     */
    public function delete(Request $request, Resource $resource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($resource->getPatchNotes() as $patchNote){
                $entityManager->remove($patchNote);
            }
            $entityManager->remove($resource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_profile');
    }
}
