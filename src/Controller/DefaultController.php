<?php

namespace App\Controller;

use App\Repository\ResourceRepository;
use App\Repository\SubscribeResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home", priority=5)
     */
    public function index(
        ResourceRepository $resourceRepository,
        SubscribeResourceRepository $subscribeResourceRepository): Response
    {
        $limit = $this->getParameter('homepage.resources.limit');

        return $this->render('default/index.html.twig', [
            'resources'    => $resourceRepository->resourceTopLimit($subscribeResourceRepository, $limit),
            'newResources' => $resourceRepository->resourceNewLimit($limit)
        ]);
    }
    /**
     * @Route("/conditions-generales-utilsateur", name="app_cgu")
     */
    public function cgu():Response
    {
        return $this->render('default/cgu.html.twig');
    }
    /**
     * @Route("/reglementation-general-protection-donnees", name="app_rgpd")
     */
    public function rgpd():Response
    {
        return $this->render('default/rgpd.html.twig');
    }

    /**
     * @Route("/conditions-general-ventes", name="app_cgv")
     */
    public function cgv():Response
    {
        return $this->render('default/cgv.html.twig');
    }
}
