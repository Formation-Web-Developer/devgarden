<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Resource;
use App\Form\ResourceType;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    /**
     * @Route("/{category_slug}/{resource_slug}", name="resource_show")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @ParamConverter("resource", options={"mapping": {"resource_slug": "slug"}})
     */
    public function show(Category $category, \App\Entity\Resource $resource): Response
    {
        if ($category->getId() !== $resource->getCategory()->getId())
        {
            throw $this->createNotFoundException('Resource not found !');
        }

        return $this->render("resource/show.html.twig", [
            'resource' => $resource,
        ]);
    }

    /**
     * @Route("/utilisateurs/profil/ressources/nouveau", name="resource_new", methods={"GET","POST"}, priority=5)
     */
    public function new(Request $request): Response
    {
        $slugify = new Slugify();
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $resource->setUser($this->getUser());
            $resource->setSlug($slugify->slugify($resource->getName()));
            $entityManager->persist($resource);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('resource/new.html.twig', [
            'resource' => $resource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/utilisateurs/profil/ressources/{id}", name="resource_edit", methods={"GET","POST"}, priority=3)
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
     * @Route("/utilisateurs/profil/ressources/supprimer/{id}", name="resource_delete", methods={"POST"}, priority=3)
     */
    public function delete(Request $request, Resource $resource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_profile');
    }
}
