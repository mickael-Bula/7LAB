<?php

namespace App\Controller;

use App\Entity\Constructeur;
use App\Form\ConstructeurType;
use App\Repository\ConstructeurRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @Route("/constructeur")
 */
class ConstructeurController extends AbstractController
{
    /** @var CacheInterface $cache */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @Route("/", name="app_constructeur_index", methods={"GET"})
     * @throws InvalidArgumentException
     */
    public function index(ConstructeurRepository $constructeurRepository): Response
    {
        // on retourne le contenu du cache soit directement, soit après avoir récupéré auprès de Doctrine si le cache est vide
        $constructors = $this->cache->get('constructors', function() use ($constructeurRepository) {
            return $constructeurRepository->findAll();
        });

        return $this->render('constructeur/index.html.twig', [
            'constructeurs' => $constructors,
        ]);
    }

    /**
     * @Route("/new", name="app_constructeur_new", methods={"GET", "POST"})
     * @throws InvalidArgumentException
     */
    public function new(Request $request, ConstructeurRepository $constructeurRepository): Response
    {
        $constructeur = new Constructeur();
        $form = $this->createForm(ConstructeurType::class, $constructeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $constructeurRepository->add($constructeur, true);

            // ajout d'un flashMessage signalant la création d'un constructeur
            $this->addFlash("success", "Un nouveau constructeur à été ajouté");

            // on supprime la clé récupérant la liste des constructeurs dans le cache qui n'est plus à jour
            $this->cache->delete("constructors");

            // suppression également du cache des véhicules
            $this->cache->delete('vehicles');

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
     * @throws InvalidArgumentException
     */
    public function edit(Request $request, Constructeur $constructeur, ConstructeurRepository $constructeurRepository): Response
    {
        $form = $this->createForm(ConstructeurType::class, $constructeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $constructeurRepository->add($constructeur, true);

            // ajout d'un flashMessage signalant la modification d'un constructeur
            $this->addFlash("success", "Les modifications ont été enregistrées");

            // on supprime la clé récupérant la liste des constructeurs dans le cache qui n'est plus à jour
            $this->cache->delete("constructors");

            return $this->redirectToRoute('app_constructeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('constructeur/edit.html.twig', [
            'constructeur' => $constructeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_constructeur_delete", methods={"POST"})
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, Constructeur $constructeur, ConstructeurRepository $constructeurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$constructeur->getId(), $request->request->get('_token'))) {
            $constructeurRepository->remove($constructeur, true);

            // ajout d'un flashMessage signalant la suppression d'un constructeur
            $this->addFlash("success", "Le constructeur a été supprimé");

            // on supprime la clé récupérant la liste des constructeurs dans le cache qui n'est plus à jour
            $this->cache->delete("constructors");
        }

        return $this->redirectToRoute('app_constructeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
