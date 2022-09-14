<?php

namespace App\Controller;

use App\Repository\ConstructeurRepository;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(VoitureRepository $voitureRepository, ConstructeurRepository $constructeurRepository): Response
    {
        $constructors = $constructeurRepository->findAll();

        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll(),
            'constructors' => $constructors,
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
