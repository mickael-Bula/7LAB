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
     * @var EventDispatcherInterface $eventDispatcher Utilisation de l'eventDispatcher pour alléger le controller
     */
    private $eventDispatcher;

    public function __construct(CacheInterface $cache, EventDispatcherInterface $eventDispatcher)
    {
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/", name="app_home", methods={"GET", "POST"})
     * @throws InvalidArgumentException
     */
    public function index(
        Request $request,
        VoitureRepository $voitureRepository,
        ConstructeurRepository $constructeurRepository
    ): Response
    {
        // Récupération du cache des véhicules et des constructeurs : si la clé n'est pas présente dans le cache, on lance la fonction de rappel en lui fournissant le repository
        $vehicles = $this->cache->get('vehicles', function() use ($voitureRepository) {
            return $voitureRepository->findAll();
        });
        $constructors = $this->cache->get('constructors', function() use ($constructeurRepository) {
            return $constructeurRepository->findAll();
        });

        // définition de la variable $voitures pour la méthode GET (en GET aucun filtre n'étant activé, on récupère tous les véhicules)
        $voitures = $request->isMethod('GET') ? $vehicles : null;

        $form = $this->createForm(ConstructeurChoiceType::class);
        $form->handleRequest($request);

        // définition des variables pour la méthode POST
        if ($form->isSubmitted() && $form->isValid()) {

            // appel de l'event dispatcher et dispatch de l'event déclaré dans le fichier config/services.yaml
            $event = new GenericEvent($request);
            $this->eventDispatcher->dispatch($event, 'filters');

            // Je récupère le résultat de la requête enregistrée dans l'objet $event (la clé "data" a été ajoutée depuis le service)
            $voitures = $event['data'];

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
