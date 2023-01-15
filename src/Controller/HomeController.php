<?php

namespace App\Controller;

use App\Form\ConstructeurChoiceType;
use App\Repository\ConstructeurRepository;
use App\Repository\VoitureRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController
{
    /** @var CacheInterface $cache Injection du cache dans le constructeur */
    private $cache;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(CacheInterface $cache, EventDispatcherInterface $eventDispatcher)
    {
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/", name="app_home", methods={"GET", "POST"})
     */
    public function index(Request $request, VoitureRepository $voitureRepository, ConstructeurRepository $constructeurRepository): Response
    {
        // récupération du cache des véhicules et des constructeurs
        $vehicles = $this->cache->get('vehicles', function() use ($voitureRepository) {
            return $voitureRepository->findAll();
        });
        $constructors = $this->cache->get('constructors', function() use ($constructeurRepository) {
            return $constructeurRepository->findAll();
        });

        // définition de la variable $voitures pour la méthode GET
        $voitures = $request->isMethod('GET') ? $vehicles : null;

        $form = $this->createForm(ConstructeurChoiceType::class);
        $form->handleRequest($request);

        // définition des variables pour la méthode POST
        if ($form->isSubmitted() && $form->isValid()) {

            // récupération des véhicules à partir des filtres sélectionnés
            $brands = $request->request->get("constructeur_choice")["name"] ?? null;
            $fuels = $request->request->get("constructeur_choice")["fuel"] ?? null;

            // appel de l'event dispatcher et dispatch de l'event déclaré dans le fichier config/services.yaml
            // Problème : le retour de l'appel au dispatcher nous donne une objet de cette classe et non le retour de la méthode qui est appellée par le service...
            $this->eventDispatcher->dispatch(new GenericEvent(null, ['brands' => $brands, 'fuels' => $fuels]), 'filters');

            // Ici, je ne fais pas de redirection parce que je veux afficher la même page
            //!\\ il n'est pas possible de faire un redirectToRoute() lorsqu'on désire passer des arguments...
        }

        return $this->renderForm('voiture/index.html.twig.', [
            'voitures' => $voitures,
            'constructors' => $constructors,
            'form' => $form,
        ]);
    }
}
