<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     * @param Request $request
     * @param ProduitRepository $produitRepository
     * @param PaginatorInterface $paginator
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(
        Request $request,
        ProduitRepository $produitRepository,
        PaginatorInterface $paginator
    ): Response {
        $allProductsQuery = $produitRepository->createQueryBuilder('p')
            ->where('p.isPublished = 1')
            ->getQuery();
        $produits = $paginator->paginate(
            $allProductsQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );
        return $this->render('produit/index.html.twig', [
            'produits'=>$produits
        ]);
    }

    /**
     * @Route("/gestion/new", name="produit_new", methods={"GET","POST"})
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
            $user = $this->getUser();
            $produit->setAuthor($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Le produit a bien été ajouté');
            //return $this->redirectToRoute('produit_index');
        }
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug<[a-z0-9\-]+>}", name="produit_show", methods={"GET"})
     * @param string $slug
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
        //$produit->setNbViews($produit->getNbViews() + 1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/gestion/edit/{slug<[a-z0-9\-]+>}", name="produit_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Produit $produit
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $user = $this->getUser();
        if ($user !== $produit->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('warning', 'Vous ne pouvez pas modifier un produit que vous n\'avez pas créé');
            throw $this->createAccessDeniedException("l'utilisateur courant n'est pas l'auteur de la publication");
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('warning', 'Le produit a bien été modifié');
            return $this->redirectToRoute('produit_index');
        }
        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{slug<[a-z0-9\-]+>}", name="produit_delete", methods={"DELETE"})
     * @IsGranted("ROLE_MODERATEUR")
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
     * @Route("/search/{searchTerm<[a-zA-Z0-9\-]+>}")
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
    public function updateNbViews(Produit $produit):JsonResponse
    {
        $views = $produit->getNbViews() + 1;
        $produit->setNbViews($views);
        $this->getDoctrine()->getManager()->flush();
        return $this->json([
            'views' => $views
        ]);
    }



}
