<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\Produit1Type;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findBy([
                'isPublished'=>true
                ])
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(Produit1Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug<[a-z0-9\_]+>}", name="produit_show", methods={"GET"})
     * @param Produit $produit
     * @return Response
     */
    public function show(string $slug): Response
    {
        throw new Exception('Test erreur 500');
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBy([
            'slug'=>$slug,
            'isPublished'=>true
        ]);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{slug<[a-z0-9\_]+>}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(Produit1Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index', [
                'slug' => $produit->getSlug(),
            ]);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug<[a-z0-9\_]+>}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }
    /**
     * @Route("/search/{searchTerm<[a-zA-Z0-9\_]+>}")
     * @param string $searchTerm
     * @return Response
     */
    public function search(string $searchTerm):Response
    {
        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBySomeField($searchTerm);

        return $this->render('produit/show.html.twig', [
            'produit'=>$produit
        ]);
    }
}
