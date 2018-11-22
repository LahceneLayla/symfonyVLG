<?php

namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\Routing\Annotation\Route;

use BoutiqueBundle\Entity\Produit;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ProduitController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        // récupérer les produits (BDD)
        // SELECT * FROM produits

        // on récupère le service Doctrine
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        // $repository est de fait le repository correspondant à la classe Produit (donc à la table produit).
        // Et me permet de faire des requêtes sur la table produit

        // SELECT * FROM produits :
        $produits = $repository->findAll();

        #--------- POUR LES BESOINS DES EXPLICATIONS LE CODE QUI SUIT - les requêtes SQL - EST LA MAIS IL DEVRAIT ETRE DANS Produit.repository.php

        // récupérer les catégories du site (BDD)
        // SELECT DISTINCT categorie FROM produit ORDER BY ASC

        // pour utiliser QueryBuilder ou CreateQuery le Manager est nécessaire
        // on est obligé d'utiliser les alias
        $em = $this->getDoctrine()->getManager();
        // ATT - depuis ProduitRepository.php cette ligne s'écrit autrement (on n'est pas dans le même namespace !) :
        // $em = $this->getEntityManager();

        // Méthode 1 avec QueryBuilder (PHP)
        // On bâtit une requête vie des fonctions PHP de Doctrine
        $query = $em->createQueryBuilder();
        $query
            ->select('p.categorie')
            ->distinct(true)
            ->from(Produit::class, 'p')
            ->orderBy('p.categorie', 'ASC');

        // On exécute la requête et on fetch
        $categories = $query->getQuery()->getResult();

        // Méthode 2 avec CreateQuery (SQL)
        // on créé une requête en SQL via Doctrine
        $query = $em->createQuery("SELECT DISTINCT p.categorie FROM BoutiqueBundle\Entity\Produit p ORDER BY p.categorie");

        // on exécute la requête et on fetch
        $categories = $query->getResult();

        #--------- FIN ~ POUR LES BESOINS DES EXPLICATIONS LE CODE QUI SUIT - les requêtes SQL - EST LA MAIS IL DEVRAIT ETRE DANS Produit.repository.php

        // transmettre les produits à la vue
        $params = [
            'produits'  => $produits,
            'categories' => $categories,
            'title' => 'Accueil'
        ];

        // return $this->render('BoutiqueBundle:Default:index.html.twig'); // à changer par : (pb de version SF)
        return $this->render('@Boutique/Produit/index.html.twig', $params);
    }

#--------------------------------------#
    /**
     * @Route("/produit/{id}", name="produit")
     * www.boutique.com/produit/12
     */
    public function produitAction($id)
    {
        // SELECT * FROM produit WHERE id_produit = $id
        // Méthode 1 :
        // on récupère le repository produit
        $repository = $this->getDoctrine()->getRepository(Produit::class); // on déclare à quelle table le repository est lié
        $produit = $repository->find($id);

        // Méthode 2 :
        // on récupère l'entityManager
        // le Manager (patron des différents repository) est capable d'intervenir sur toutes les tables de la BDD
        // mais par ex. findAll() n'existe pas sur le Manager et Delete n'existe pas dans le repository
        $em = $this->getDoctrine()->getManager();
        $produit = $em->find(Produit::class, $id);


        // Suggestion de produits
        // SELECT * FROM produit WHERE categorie = produit['categorie'] AND id_produit != $id AND stock > 0 ORDER BY prix DESC LIMIT 0,5
        $categorie = $produit->getCategorie();
        $suggestions = $repository->findBy(['categorie' => $categorie], ['prix' => 'DESC'], 3, 0);


        // transmettre les produits à la vue
        $params = [
            'produit'  => $produit,
            'suggestions' => $suggestions,
            'title' => 'Produit : ' . $produit->getTitre()
        ];

        return $this->render('@Boutique/Produit/produit.html.twig', $params);
// par convention non écrite on verrait dans les projets SF => return $this->render('@Boutique/Produit/show.html.twig');
    }
#--------------------------------------#

    /**
     * @Route("/categorie/{cat}", name="categorie")
     * www.boutique.com/categorie/tshirt
     */
    public function categorieAction($cat)
    {
        // récupérer les produits (BDD)
        // SELECT * FROM produit WHERE categorie = '$cat' -> fetchAll()
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repository->findBy(['categorie' => $cat]);
        // tester url http://localhost:8000/categorie/robe


        // récupérer les catégories du site (BDD)
        // SELECT DISTINCT categorie FROM produit

        // Méthode 1 QueryBuilder() via ProduitRepository
        $categories = $repository->findAllCategorie();

        // Méthode 2 CreateQuery() via ProduitRepository
        $categories = $repository->findAllCategorie2();

        // transmettre les produits à la vue
        $params = [
            'produits'  => $produits,
            'categories' => $categories,
            'cat' => $cat,
            'title' => 'Rayon : ' . $cat
        ];

        return $this->render('@Boutique/Produit/index.html.twig', $params);
    }

    #--------------------------------------#
    // AJOUTER UN PRODUIT (Admin)
    /**
     * @Route("/admin/produit/add", name="add_produit")
     */
    //public function addProduitAction()
    //{
        // on instancie un objet de notre Entity Produit
        // il nous permet de manipuler un enregistrement dans la table produit
    //    $produit = new Produit();

     //   $produit
            // ->setReference('0900')
            // ->setCategorie('Tshirt')
            // ->setTitre('Magnifik')
            // ->setDescription('Lorem ipsum')
            // ->setCouleur('vert')
            // ->setTaille('XL')
            // ->setPublic('m')
            // ->setPhoto('ts_rouge.jpg')
            // ->setPrix(14.99)
            // ->setStock(79);

        // on récupère le manager pour pouvoir faire une insertion
    //    $em = $this->getDoctrine()->getManager();

        // on prépare l'insertion
        //$em->persist($produit);

        // on enregistre
        //$em->flush();

        //return new Response("Le produit a bien été enregistré.");

        // Test => localhost:8000/admin/produit/add
   // }

    #--------------------------------------#

    // MODIFIER UN PRODUIT (Admin)

    /**
     * @Route("/admin/produit/update/{id}", name="update_produit")
     */
   // public function updateProduitAction($id)
   // {
        // on récupère le manager
       // $em = $this->getDoctrine()->getManager();

        // on récupère le produit à modifier par son $id sous forme d'un objet (Doctrine manipule des objets)
       // $produit = $em->find(Produit::class, $id);

        // on modifie une info (peu importe quel champs)
       // $produit->setTitre('TeeRed');

        // on prépare l'insertion
       // $em->persist($produit);

        // on enregistre
       // $em->flush();

       // return new Response('Le produit n°'. $produit->getIdProduit() .'a bien été modifié.');

        // Test => localhost:8000/admin/produit/update/13
   // }

    #--------------------------------------#

    // SUPPRIMER UN PRODUIT (Admin)

    /**
     * @Route("/admin/produit/delete/{id}", name="delete_produit")
     */
    //public function deleteProduitAction($id, Request $request)
    //{
        // on récupère le manager
        //$em = $this->getDoctrine()->getManager();

        // on récupère le produit à modifier par son $id sous forme d'un objet (Doctrine manipule des objets)
        //$produit = $em->find(Produit::class, $id);

        // on supprime
        //$em->remove($produit);

        //$em->flush();

        //$session = $request->getSession();
        //$session->getFlashBag()->add('success', 'Le produit a été supprimé avec succès');

        //return $this->redirectToRoute('home');

        // Test => localhost:8000/admin/produit/delete/13
    //}



}

