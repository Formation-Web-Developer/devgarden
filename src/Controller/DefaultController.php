<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home", priority=5)
     */
    public function index(CategoryRepository $categoryRepository,ResourceRepository $resourceRepository): Response
    {

        return $this->render('default/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'resources'   => $resourceRepository->resourceLimitHome()
        ]);
    }
    /**
     * @Route("/conditions-generales-utilsateurs", name="app_cgu")
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
