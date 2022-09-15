<?php

namespace App\Controller;

use App\Form\ConstructeurChoiceType;
use App\Repository\ConstructeurRepository;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET", "POST"})
     */
    public function index(Request $request, VoitureRepository $voitureRepository, ConstructeurRepository $constructeurRepository): Response
    {
        // définition des variables pour la méthode GET
        $voitures = $request->isMethod('GET') ? $voitureRepository->findAll() : null;
        $constructors = $constructeurRepository->findAll();

        $form = $this->createForm(ConstructeurChoiceType::class);
        $form->handleRequest($request);

        // définition des variables pour la méthode POST
        if ($form->isSubmitted() && $form->isValid()) {

            // récupération des "boxes checked" avec une requête personnalisée, sinon on retourne tous les véhicules
            $inputs = $request->request->get("constructeur_choice")["name"] ?? null;
            $voitures = isset($inputs) ? $voitureRepository->findCarsByConstructors(array_values($inputs)) : $voitureRepository->findAll();

            // il n'est pas possible de faire un redirectToRoute() lorsqu'on veut passer des arguments...
            return $this->renderForm('voiture/index.html.twig', [
                'constructeurs' => $constructeurRepository->findAll(),
                'form' => $form,
                'voitures' => $voitures,
            ]);
        }

        return $this->renderForm('voiture/index.html.twig', [
            'voitures' => $voitures,
            'constructors' => $constructors,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/filter/{id}", name="app_filter", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function filter(VoitureRepository $voitureRepository, ConstructeurRepository $constructeurRepository, int $id): Response
    {
        $constructors = $constructeurRepository->findAll();

        $voitures = $voitureRepository->findBy(["constructor" => $id]);

        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
            'constructors' => $constructors,
        ]);
    }
}
