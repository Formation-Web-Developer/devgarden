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
 * @Route("/{category_slug}")
 */
class ResourceController extends AbstractController
{
    /**
     * @Route("/", name="category_resource")
     */
    public function index(ResourceRepository $repository, string $category_slug): Response
    {
        $resources = $repository->getResourceByCategoryLimit($category_slug);
        if (empty($resources)) {
            throw $this->createNotFoundException('Category not found or empty !');
        }
        return $this->render('default/index.html.twig', [
            'resources' => $resources
        ]);
    }

    /**
     * @Route("/{resource_slug}", name="resource_show")
     */
    public function show(ResourceRepository $repository, string $category_slug, string $resource_slug): Response
    {
        return $this->render("resource/show.html.twig", [
            'resource' => $this->getResourceBySlug($repository, $category_slug, $resource_slug)
        ]);
    }

    /**
     * @Route("/{resource_slug}/versions", name="patch_notes_index")
     */
    public function indexPatch(ResourceRepository $repository, string $category_slug, string $resource_slug): Response
    {
        return $this->render("resource/patch_note.html.twig", [
            'resource' => $this->getResourceBySlug($repository, $category_slug, $resource_slug)
        ]);
    }

    /**
     * @Route("/{resource_slug}/versions/{patch_note_slug}", name="patch_notes_show")
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
