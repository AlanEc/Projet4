<?php

namespace Louvre\ResaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NombreBilletVenduValidator extends ConstraintValidator
{
	private $requestStack;
	private $em;

	public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
	{
		$this->requestStack = $requestStack;
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

    $jourVisite = $value;
    $formatJourVisite = $jourVisite->format('Y-m-d H:i:s');
    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $formatJourVisite);
    $date = $date->format('Y-m-d');

    if ($totalNombreBillet >= 1000) {
      $this->context->addViolation($constraint->message);
    }

	}
}