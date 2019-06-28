<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
                'isPublished' => true
            ])
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $produit->updateSlug();
            $directory = 'img/';
            $file = $form['imageName']->getData();
            if ($file) {
                $newFileName = $produit->getSlug() . date_format($date, 'U') . '.' . $file->guessExtension();
                try {
                    $file->move($directory, $newFileName);
                    $produit->setImageName($newFileName);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            $produit->setCreationDate($date);
            $produit->setNbViews(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Le produit a bien été ajouté');
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
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBy([
            'slug' => $slug,
            'isPublished' => true
        ]);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $produit->setNbViews($produit->getNbViews() + 1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{slug<[a-z0-9\_]+>}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('warning', 'Le produit a bien été modifié');
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
        if ($this->isCsrfTokenValid('delete' . $produit->getSlug(), $request->request->get('_token'))) {
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
    public function search(string $searchTerm): Response
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBySomeField($searchTerm);
        return $this->render('produit/show.html.twig', [
            'produit' => $produit
        ]);
    }
}
