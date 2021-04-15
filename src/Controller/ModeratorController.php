<?php

namespace App\Controller;

use App\Form\ValidatorType;
use App\Repository\ResourceRepository;
use App\Utils\State;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class ModeratorController extends AbstractController
{
    /**
     * @Route("/ressources/en-attente", name="waiting_resources", priority="5")
     */
    public function waitingResource(ResourceRepository $resourceRepository): Response
    {
        return $this->render('admin/moderator/index.html.twig', [
            'waitingResource' => $resourceRepository->waitingResources(),
        ]);
    }

    /**
     * @Route("/ressources/en-attente/{id}", name="show_waiting_resources", priority="3")
     */
    public function showWaitingResource(\App\Entity\Resource $resource, Request $request): Response
    {
        $form = $this->createForm(ValidatorType::class, $resource);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resource);
            $entityManager->flush();
            switch ($resource->getValidation()) {
                case State::VALIDATED:
                    $this->addFlash('success','La Ressource a bien été accepté');
                    break;
                case State::REFUSED:
                    $this->addFlash('error','La Ressource a bien été refusé');
                    break;
                default:
                    $this->addFlash('warning','La Ressource est en attente');
            }
        }
        return $this->render('admin/resource/show-waiting.html.twig', [
            'resource' => $resource,
            'form'     => $form->createView()
        ]);
    }

}
