<?php

namespace App\Controller;

use App\Entity\PatchNote;
use App\Entity\Resource;
use App\Form\PatchNoteType;
use App\Repository\PatchNoteRepository;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateurs/profil/ressources/{slug}/versions", name="patch_note_")
 * @ParamConverter("resource", options={"mapping": {"slug": "slug"}})
 */
class ResourcePatchNoteController extends AbstractController
{
    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, \App\Entity\Resource $resource): Response
    {
        $patchNote = new PatchNote();
        $slugify = new Slugify();
        $form = $this->createForm(PatchNoteType::class, $patchNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patchNote);
            $patchNote->setLink('');
            $patchNote->setSlug($slugify->slugify($patchNote->getVersion()));
            $patchNote->setResource($resource);
            $entityManager->flush();

            return $this->redirectToRoute('patch_notes_index', [
                'category_slug' => $resource->getCategory()->getSlug(),
                'resource_slug' => $resource->getSlug()
            ]);
        }

        return $this->render('resource/patch_note/new.html.twig', [
            'patch_note' => $patchNote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @ParamConverter("pacthNote", options={"mapping": {"id": "id"}})
     */
    public function edit(Request $request, \App\Entity\Resource $resource, PatchNote $patchNote): Response
    {
        $form = $this->createForm(PatchNoteType::class, $patchNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('patch_notes_index', [
                'category_slug' => $resource->getCategory()->getSlug(),
                'resource_slug' => $resource->getSlug()
            ]);
        }

        return $this->render('resource/patch_note/edit.html.twig', [
            'patch_note' => $patchNote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     * @ParamConverter("pacthNote", options={"mapping": {"id": "id"}})
     */
    public function delete(Request $request, PatchNote $patchNote, \App\Entity\Resource $resource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patchNote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($patchNote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('patch_notes_index', [
            'category_slug' => $resource->getCategory()->getSlug(),
            'resource_slug' => $resource->getSlug()
        ]);
    }
}
