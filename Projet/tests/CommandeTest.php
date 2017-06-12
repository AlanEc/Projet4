<?php
namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandeTest extends WebTestCase
{
	public function testHeureSuperieur() {
		$this->assertGreaterThan(15, \Louvre\ResaBundle\Entity\Commande::isContentValid(14) );
	}

}