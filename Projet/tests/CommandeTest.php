<?php
namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Louvre\ResaBundle\Entity\Commande;
use Louvre\ResaBundle\Entity\Billet;



class CommandeTest extends WebTestCase
{
	public function testCommande() {

		$commande = new commande;
		$commande->setPrixTotal('10');
		$this->assertEquals(10, $commande->getPrixTotal());
	}

	public function testCommandeTotal() {

		$commande = new commande;
		$billet = new billet;
		$prixBillet = $billet->setPrix('12');
		$commande->setPrixTotal($prixBillet);
		$this->assertEquals($prixBillet, $commande->getPrixTotal());
	}

}