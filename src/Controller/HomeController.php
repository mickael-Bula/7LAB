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

            // récupération des véhicules à partir des filtres sélectionnés
            $brands = $request->request->get("constructeur_choice")["name"] ?? null;
            $fuels = $request->request->get("constructeur_choice")["fuel"] ?? null;

            // je lance la requête avec les paramètres récupérés
            $voitures = $voitureRepository->findCarsByFilters($brands, $fuels);

            // Ici je ne fais pas de redirection parce que je veux à afficher la même page
            //!\\ il n'est pas possible de faire un redirectToRoute() lorsqu'on veut passer des arguments...
        }

        return $this->renderForm('voiture/index.html.twig.', [
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
