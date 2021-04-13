<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\PatchNote;
use App\Entity\Resource;
use App\Entity\User;
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
     * @Route("/{category_slug}/{resource_slug}/versions", name="patch_notes_index")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @ParamConverter("resource", options={"mapping": {"resource_slug": "slug"}})
     */
    public function indexPatch(Category $category, \App\Entity\Resource $resource): Response
    {
        if ($category->getId() !== $resource->getCategory()->getId())
        {
            throw $this->createNotFoundException('Resource not found !');
        }
        return $this->render("resource/patch_note.html.twig", [
            'resource' => $resource,
            'patchNotes' => $resource->getPatchNotes()
        ]);
    }
    /**
     * @Route("/{category_slug}/{resource_slug}/versions/{patch_note_slug}", name="patch_notes_show")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @ParamConverter("resource", options={"mapping": {"resource_slug": "slug"}})
     * @ParamConverter("patchNote", options={"mapping": {"patch_note_slug": "slug"}})
     */
    public function showPatch(Category $category, \App\Entity\Resource $resource, PatchNote $patchNote): Response
    {
        if ($category->getId() !== $resource->getCategory()->getId())
        {
            throw $this->createNotFoundException('Resource not found !');
        }

        return $this->render("resource/patch_note/index.html.twig", [
            'patchNote' => $patchNote
        ]);
    }
}
