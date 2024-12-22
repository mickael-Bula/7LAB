<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[Route(path: '/voiture')]
class VoitureController extends AbstractController
{
    public function __construct(
        /** @var CacheInterface $cache Injection du cache dans le constructeur */
        private readonly CacheInterface $cache
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route(path: '/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VoitureRepository $voitureRepository): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->add($voiture, true);

            // on supprime la clé récupérant la liste des voitures dans le cache qui n'est plus à jour
            $this->cache->delete("vehicles");

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route(path: '/{id}', name: 'app_voiture_show', methods: ['GET'])]
    public function show(Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        // récupération en cache d'un véhicule à partir de son Id
        $vehicle = $this->cache->get('vehicle' . $voiture->getId(), fn() => $voitureRepository->findOneBy(["id" => $voiture->getId()]));

        return $this->render('voiture/show.html.twig', [
            'voiture' => $vehicle,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route(path: '/{id}/edit', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,
                         Voiture $voiture,
                         VoitureRepository $voitureRepository,
                         LoggerInterface $logger,
                         SerializerInterface $serializer
    ): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->add($voiture, true);

            // ajout d'un flashMessage signalant la modification
            $this->addFlash('success', 'Le véhicule a été modifié avec succès !');

            // suppression du cache des voitures qui n'est plus à jour
            $this->cache->delete("vehicles");

            // ajout d'un log dans le fichier /var/log/dev.log
            // NOTE : je précise que les champs concernés par la sérialisation appartiennent au groupe 'car-edit'
            $jsonContent = $serializer->serialize($voiture, 'json', ['groups' => 'car-edit']);
            $logger->info($jsonContent);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route(path: '/{id}', name: 'app_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voiture->getId(), $request->request->get('_token'))) {
            $voitureRepository->remove($voiture, true);

            // suppression du cache des voitures
            $this->cache->delete("vehicles");
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
