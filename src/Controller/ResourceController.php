<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\PatchNote;
use App\Entity\Resource;
use App\Entity\User;
use App\Form\ResourceType;
use App\Repository\PatchNoteRepository;
use App\Repository\ResourceRepository;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{category_slug}/{resource_slug}")
 */
class ResourceController extends AbstractController
{
    /**
     * @Route("/", name="resource_show")
     */
    public function show(ResourceRepository $repository, string $category_slug, string $resource_slug): Response
    {
        if (!($resource = $repository->getByCategoryAndSlug($category_slug, $resource_slug)))
        {
            throw $this->createNotFoundException('Resource not found !');
        }
        return $this->render("resource/show.html.twig", [
            'resource' => $resource,
        ]);
    }

    /**
     * @Route("/versions", name="patch_notes_index")
     */
    public function indexPatch(ResourceRepository $repository, string $category_slug, string $resource_slug): Response
    {
        if (!($resource = $repository->getByCategoryAndSlug($category_slug, $resource_slug)))
        {
            throw $this->createNotFoundException('Resource not found !');
        }
        return $this->render("resource/patch_note.html.twig", [
            'resource' => $resource,
            'patchNotes' => $resource->getPatchNotes()
        ]);
    }
    /**
     * @Route("/versions/{patch_note_slug}", name="patch_notes_show")
     */
    public function showPatch(
        PatchNoteRepository $repository, string $category_slug,
        string $resource_slug, string $patch_note_slug
    ): Response
    {
        if(!($patchNote = $repository->getByResourceAndCategorySlug($category_slug, $resource_slug, $patch_note_slug)))
        {
            throw $this->createNotFoundException('Patch Note not found !');
        }
        return $this->render("resource/patch_note/index.html.twig", [
            'patchNote' => $patchNote
        ]);
    }
}
