<?php

namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use BoutiqueBundle\Entity\Produit; 
use BoutiqueBundle\Entity\Membre; 
use BoutiqueBundle\Entity\Commande; 

use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request; 

class AdminController extends Controller
{
	
	
	/**
	*@Route("/admin/produit/show/", name="show_produit")
	*
	*/
	public function showProduitAction(){
		
		$repository = $this -> getDoctrine() -> getRepository(Produit::class);
		
		$produits = $repository -> findAll();
		
		$params = array(
			'produits' => $produits,
			'title' => 'Gestion des produits'
		);
		
		return $this -> render("@Boutique/Admin/show_produit.html.twig", $params);
	}
	
	/**
	*@Route("/admin/produit/delete/{id}", name="delete_produit")
	*
	*/
	public function deleteProduitAction($id, Request $request){
		
		$em = $this -> getDoctrine() -> getManager();	
		$produit = $em -> find(Produit::class, $id);	
		$session = $request -> getSession();
		
		if($produit){
			
			$em -> remove($produit);
			$em -> flush();
			
			$session -> getFlashBag() -> add('success', 'Le produit a bien été supprimé');	
		}
		else{
			$session -> getFlashBag() -> add('error', 'Le produit n\'existe pas');	
		}
		
		return $this -> redirectToRoute("show_produit");
	}
	
	/**
	*@Route("/admin/commande/show/", name="show_commande")
	*
	*/
	public function showCommandeAction(){
		
		$repository = $this -> getDoctrine() -> getRepository(Commande::class);
		
		$commandes = $repository -> findAll();
		
		$params = array(
			'commandes' => $commandes,
			'title' => 'Gestion des commandes'
		);
		
		return $this -> render('@Boutique/Admin/show_commande.html.twig', $params);
	}
	
	/**
	*@Route("/admin/commande/delete/{id}", name="delete_commande")
	*
	*/
	public function deleteCommandeAction($id, Request $request){

		$em = $this -> getDoctrine() -> getManager();	
		$commande = $em -> find(commande::class, $id);	
		$session = $request -> getSession();
		
		if($commande){
			
			$em -> remove($commande);
			$em -> flush();
			
			$session -> getFlashBag() -> add('success', 'La commande a bien été supprimé');	
		}
		else{
			$session -> getFlashBag() -> add('error', 'La commande n\'existe pas');	
		}
		
		return $this -> redirectToRoute("show_commande");
		
	}
	
	/**
	*@Route("/admin/membre/show/", name="show_membre")
	*
	*/
	public function showMembreAction(){
		$repository = $this -> getDoctrine() -> getRepository(Membre::class);
		
		$membres = $repository -> findAll();
		
		$params = array(
			'membres' => $membres,
			'title' => 'Gestion des membres'
		);
		
		return $this -> render('@Boutique/Admin/show_membre.html.twig', $params);	
	}
	
	/**
	*@Route("/admin/membre/delete/{id}", name="delete_membre")
	*
	*/
	public function deleteMembreAction($id, Request $request){
		
		$em = $this -> getDoctrine() -> getManager();	
		$membre = $em -> find(membre::class, $id);	
		$session = $request -> getSession();
		
		if($membre){
			
			$em -> remove($membre);
			$em -> flush();
			
			$session -> getFlashBag() -> add('success', 'Lmembre a bien été supprimé');	
		}
		else{
			$session -> getFlashBag() -> add('error', 'Le membre n\'existe pas');	
		}
		
		return $this -> redirectToRoute("show_membre");
	}
	
	
}


