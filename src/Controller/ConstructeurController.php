<?php

namespace App\Controller;

use App\Entity\Constructeur;
use App\Form\ConstructeurType;
use App\Repository\ConstructeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/constructeur")
 */
class ConstructeurController extends AbstractController
{
    /**
     * @Route("/", name="app_constructeur_index", methods={"GET"})
     */
    public function index(ConstructeurRepository $constructeurRepository): Response
    {
        return $this->render('constructeur/index.html.twig', [
            'constructeurs' => $constructeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_constructeur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ConstructeurRepository $constructeurRepository): Response
    {
        $constructeur = new Constructeur();
        $form = $this->createForm(ConstructeurType::class, $constructeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $constructeurRepository->add($constructeur, true);

            return $this->redirectToRoute('app_constructeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('constructeur/new.html.twig', [
            'constructeur' => $constructeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_constructeur_show", methods={"GET"})
     */
    public function show(Constructeur $constructeur): Response
    {
        return $this->render('constructeur/show.html.twig', [
            'constructeur' => $constructeur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_constructeur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Constructeur $constructeur, ConstructeurRepository $constructeurRepository): Response
    {
        $form = $this->createForm(ConstructeurType::class, $constructeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $constructeurRepository->add($constructeur, true);

            return $this->redirectToRoute('app_constructeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('constructeur/edit.html.twig', [
            'constructeur' => $constructeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_constructeur_delete", methods={"POST"})
     */
    public function delete(Request $request, Constructeur $constructeur, ConstructeurRepository $constructeurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$constructeur->getId(), $request->request->get('_token'))) {
            $constructeurRepository->remove($constructeur, true);
        }

        return $this->redirectToRoute('app_constructeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
