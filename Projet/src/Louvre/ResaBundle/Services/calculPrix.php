<?php
namespace Louvre\ResaBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Louvre\ResaBundle\Entity\Commande;
use Louvre\ResaBundle\Entity\Billet;

class CalculPrix
{
  public function calcul($commande)
  {   
      $prixTotal = 0;
      foreach ($commande->getBillets() as $billet) {
     

        if ($billet->getTarifReduit() == 1) {
          $prix = 10;
        }

        else {
          $datetime1 = $billet->getDateNaissance();
          $datetime2 = new \DateTime;
          $interval = $datetime1->diff($datetime2);

          $age = $interval->format('%y');

          if ($age > 60) {
            $prix = 12;
          }

          if ($age > 12 && $age < 60) {
            $prix = 16;
          }

          if ($age > 4 && $age < 12) {
            $prix = 8;
          }

          if ($age < 4) {
            $prix = 0;
          }
        }

        $billet->setPrix($prix);
        $prixTotal += $prix;
      } 

      return $prixTotal;   
  }
}