<?php
namespace Tests;

use Louvre\ResaBundle\Entity\Billet;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilletTest extends WebTestCase
{
	public function testBillet() {

		$billet = new billet;
		$billet->setPrix('10');
		$this->assertEquals(10, $billet->getPrix());
	}

}