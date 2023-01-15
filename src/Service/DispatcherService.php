<?php

namespace App\Service;

use App\Repository\VoitureRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DispatcherService implements EventSubscriberInterface
{
    /**
     * @var VoitureRepository
     */
    private $voitureRepository;

    public function __construct(VoitureRepository $voitureRepository)
    {
        $this->voitureRepository = $voitureRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return ['filters' => 'onFilters'];
    }

    public function onFilters(GenericEvent $event)
    {
        // je lance la requête avec les paramètres récupérés
        $voitures = $this->voitureRepository->findCarsByFilters($event->getArguments()["brands"], $event->getArguments()["fuels"]);
        // le service est bien appelé, cela fonctionne, mais je ne peux retourner le résultat vers la vue...
        // C'est donc depuis le service que les actions doivent se faire, mais on ne retourne pas dans le controller...
        dump($voitures);
        return $voitures;
    }
}