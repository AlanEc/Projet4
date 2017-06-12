<?php

namespace Louvre\ResaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NombreBilletVenduValidator extends ConstraintValidator
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em           = $em;
	}

	public function validate($value, Constraint $constraint)
	{
    $repositoryCommande = $this->em
    ->getRepository('LouvreResaBundle:Commande');

    $listeCommande = $repositoryCommande->findBy(
    array('jourVisite' => $value)
    );

    $repository = $this->em
    ->getRepository('LouvreResaBundle:Billet');

    $totalNombreBillet = 0;

    foreach ($listeCommande as $reservation) {
      $listeBillet = $repository->findBy(
      array('commande' => $reservation->getId())
      );
      $nombreBillet = count($listeBillet);
      $totalNombreBillet += $nombreBillet;
    }

    if ($totalNombreBillet >= 1000) {
      $this->context->addViolation($constraint->message);
    }

	}
}