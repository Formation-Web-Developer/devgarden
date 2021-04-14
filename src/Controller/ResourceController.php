<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\PatchNote;
use App\Entity\Resource;
use App\Entity\User;
use App\Form\ResourceType;
use App\Repository\PatchNoteRepository;
use App\Repository\ResourceRepository;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

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

    /**
     * @Route("/{resource_slug}/commentaires", name="resource_comments", methods={"GET"})
     */
    public function comments(
        ResourceRepository $repository, string $category_slug,
        string $resource_slug, SerializerInterface $serializer
    ): JsonResponse
    {
        $resource = $this->getResourceBySlug($repository, $category_slug, $resource_slug);
        return $this->json($serializer->serialize($resource->getComments(), 'json', [
            'groups' => ['comment']
        ]));
    }

    /**
     * @Route("/{resource_slug}/commentaires/nouveau", name="resource_send_comment", methods={"POST"})
     */
    public function sendComment(
        ResourceRepository $repository, string $category_slug,
        string $resource_slug, Request $request
    ): JsonResponse
    {
        if(!$this->getUser()) {
            return $this->json(['type' => 'error', 'reason' => 'Accès refusé !']);
        }
        $resource = $this->getResourceBySlug($repository, $category_slug, $resource_slug);
        $message = $request->request->get('message') ?? '';
        if (mb_strlen($message) < 10 || mb_strlen($message) > 2048) {
            return $this->json(['type' => 'error', 'reason' => 'Le nombre de caractère doit-être compris entre 10 et 255']);
        }

        $comment = (new Comment())
            ->setAuthor($this->getUser())
            ->setResource($resource)
            ->setComment($message);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->json(['type' => 'success']);
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
