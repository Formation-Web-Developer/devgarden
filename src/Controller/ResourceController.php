<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
