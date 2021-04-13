<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\ResourceType;
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
            $resource->setLatest(null);
            foreach ($resource->getPatchNotes() as $patchNote){
                $entityManager->remove($patchNote);
            }
            $entityManager->remove($resource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_profile');
    }
}
