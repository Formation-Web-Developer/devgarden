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
        return $this->render("resource/show.html.twig", [
            'resource' => $this->getResourceBySlug($repository, $category_slug, $resource_slug),

        ]);
    }

    /**
     * @Route("/versions", name="patch_notes_index")
     */
    public function indexPatch(ResourceRepository $repository, string $category_slug, string $resource_slug): Response
    {
        return $this->render("resource/patch_note.html.twig", [
            'resource' => $this->getResourceBySlug($repository, $category_slug, $resource_slug)
        ]);
    }

    /**
     * @Route("/versions/{patch_note_slug}", name="patch_notes_show", methods={"GET"})
     */
    public function showPatch(
        PatchNoteRepository $repository, string $category_slug,
        string $resource_slug, string $patch_note_slug
    ): Response
    {
        return $this->render("resource/patch_note/index.html.twig", [
            'patchNote' => $this->getPatchNoteBySlug($repository, $category_slug, $resource_slug, $patch_note_slug)
        ]);
    }

    /**
     * @Route("/versions/{patch_note_slug}/latest", name="patch_note_latest", methods={"POST"})
     */
    public function latest(
        PatchNoteRepository $repository, string $category_slug,
        string $resource_slug, string $patch_note_slug
    ): Response
    {
        $repository->updateLatest($this->getPatchNoteBySlug($repository, $category_slug, $resource_slug, $patch_note_slug));
        return $this->redirectToRoute('patch_notes_show',[
            'category_slug'   => $category_slug,
            'resource_slug'   => $resource_slug,
            'patch_note_slug' => $patch_note_slug
        ]);
    }

    private function getResourceBySlug(
        ResourceRepository $repository, string $categorySlug, string $resourceSlug
    ): ?\App\Entity\Resource
    {
        if (!($resource = $repository->getByCategoryAndSlug($categorySlug, $resourceSlug)))
        {
            throw $this->createNotFoundException('Resource not found !');
        }
        return $resource;
    }

    private function getPatchNoteBySlug(
        PatchNoteRepository $repository, string $categorySlug,
        string $resourceSlug, string $patchNoteSlug
    ): ?PatchNote
    {
        if (!($patchNote = $repository->getByResourceAndCategorySlug($categorySlug, $resourceSlug, $patchNoteSlug)))
        {
            throw $this->createNotFoundException('Patch Note not found !');
        }
        return $patchNote;
    }
}
