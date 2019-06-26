<?php
namespace App\Controller;

use App\Entity\Produit;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Affiche et traite le formulaire d'ajout d'un produit
     * @Route("/produit/creation/{id<[0-9\-]+>}/{update<[0-1]>}", methods={"GET", "POST"}, name="app_produit_create")
     * @param Request $requestHTTP
     * @param int|null $id
     * @param int|null $update
     * @return Response
     * @throws Exception
     */
    public function create(Request $requestHTTP, ?int $id = null, ?int $update = 0):Response
    {
        // Création du produit
        if ($requestHTTP->getMethod() == 'POST') {
            if ($update == 0) {
                $produit = new Produit();
            } else {
                $repository=$this->getDoctrine()->getRepository(Produit::class);
                $produit = $repository->findOneBy(['id' => $id]);
            }
            $produit
                ->setName($requestHTTP->request->get('name'))
                ->setDescription($requestHTTP->request->get('desc'))
                ->setPrice($requestHTTP->request->get('prix'))
                ->setCreationDate(new DateTime($requestHTTP->request->get('date')))
                ->setModifiedDate(new DateTime())
                ->setIsPublished(0);
            $update = $requestHTTP->request->get('update');
            // Récupération du manager d'entité de Doctrine
            $manager = $this->getDoctrine()->getManager();
            // Préparation de la requête SQL
            if ($update == 0) {
                $manager->persist($produit);
            }
            // Exécution de la requête SQL (INSERT INTO ...)
            $manager->flush();
            // Redirection vers le détail du produit
            return $this->redirectToRoute('app_produit_list');
        } else {
            if ($id) {
                $repository=$this->getDoctrine()->getRepository(Produit::class);
                $produit = $repository->findOneBy(['id' => $id]);
                $CreationDate = date_format($produit->getCreationDate(), 'Y-m-d');
            } else {
                $produit = new Produit();
                $CreationDate = '';
            }
            return $this->render('produit/create.html.twig', [
                'update'=>$update,
                'produit'=>$produit,
                'CreationDate'=>$CreationDate
            ]);
        }
    }

    /**
     * Cette fonction permet de modifier un produit
     * @Route("/produit/{id<[0-9\-]+>}/modif")
     * @param int $id
     * @return Response
     */
    public function update(int $id): Response
    {
        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBy(['id' => $id]);
        // Modifications de l'article : par exemple le nom
        //$produit->setName('Nouveau nom juste pour tester les modifications');
        // Récupération du manager
        //$manager = $this->getDoctrine()->getManager();
        // Exécution du SQL (ALTER TABLE ...)
        //$manager->flush();
        // Renvoi de l'article à la vue
        return $this->redirectToRoute('app_produit_create', [
            'id'=>$id,
            'update'=>'1',
        ]);
    }

    /**
     * @Route("/produit/{id<[0-9\-]+>}/suppression")
     * @param Produit $produit
     * @return Response
     */
    public function delete(Produit $produit): Response
    {
        // Récupération du manager
        $manager = $this->getDoctrine()->getManager();
        // Préparation de la suppression
        $manager->remove($produit);
        // Exécution du SQL (DELETE FROM ...)
        $manager->flush();
        // Renvoi de l'article à la vue
        return $this->redirectToRoute('app_produit_list');
    }

    /**
     * @Route("/produit/{id<[0-9\-]+>}", name="app_produit_show")
     * @param int $id
     * @return Response
     */
    public function show(int $id):Response
    {
        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBy(['id' => $id]);
        if (!$produit) {
            throw $this->createNotFoundException("Aucun article trouvé");
        }
        return $this->render('/produit/detailProduit.html.twig', [
            'id'=>$id,
            'name'=>$produit->getName(),
            'desc'=>$produit->getDescription(),
            'price'=>$produit->getPrice(),
            'creationDate'=>$produit->getCreationDate()
        ]);
    }
    /**
     * @Route("/produit", name="app_produit_list")
     * @return Response
     */
    public function list():Response
    {
        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produits = $repository->findAll();
        return $this->render('produit/produit.html.twig', ['produits'=>$produits]);
    }

    /**
     * @Route("/produit/search/{searchTerm<[a-zA-Z0-9\-]+>}")
     * @param string $searchTerm
     * @return Response
     */
    public function search(string $searchTerm):Response
    {
        $repository=$this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->findOneBySomeField($searchTerm);
        return $this->render('produit/detailProduit.html.twig', [
            'id'=>$produit->getId(),
            'name'=>$produit->getName(),
            'desc'=>$produit->getDescription(),
            'price'=>$produit->getPrice(),
            'creationDate'=>$produit->getCreationDate()
        ]);
    }
}
