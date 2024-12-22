<?php

namespace App\Service;

use App\Repository\VoitureRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class DispatcherService implements EventSubscriberInterface
{
    public function __construct(private readonly VoitureRepository $voitureRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['filters' => 'onFilters'];
    }

    public function onFilters(GenericEvent $event): void
    {
        /**
         * Je précise que ma variable est un objet Request pour bénéficier de l'auto-complétion
         * @var Request $request Récupération de l'objet Request passé comme $subject de $event et j'initialise les variables $brands et $fuels avec les données contenues dans l'objet
         */
        $request = $event->getSubject();

        $brands = $request->request->get("constructeur_choice")["name"] ?? null;
        $fuels = $request->request->get("constructeur_choice")["fuel"] ?? null;

        // je lance la requête avec les paramètres récupérés, puis j'hydrate le tableau des arguments de l'objet $event pour transmettre au controller les données récupérées
        $event['data'] = $this->voitureRepository->findCarsByFilters($brands, $fuels);
    }
}