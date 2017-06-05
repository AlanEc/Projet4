<?php

namespace Louvre\ResaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Session\Session;

class DateReservationImpossibleValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$verifJourAnnee = date('w', strtotime($value->format('Y-m-d')));
		$verifJour = date('z', strtotime($value->format('Y-m-d')));

		/*Recuperation date d'aujourd'hui puis création format */
		$dateAujourdhui = new \DateTime();
		$verifAujourdhui = date('z', strtotime($dateAujourdhui->format('Y-m-d')));

		/* Suppression dimanche */
		if ($verifJour == '0') {
			$this->context->addViolation($constraint->message);
		}

		// /* Suppression des jours fériés */
		// elseif ($verifJourAnnee == '358' or $verifJourAnnee == '304' or $verifJourAnnee == '121') {
		// 	$this->context->addViolation($constraint->message);
		// }

		// /*Supprimer possibilité de reserver à une date inférieur à celle d'aujourd'hui*/
		// elseif ($verifAujourdhui > $verifJourAnnee ) {
		// 	$this->context->addViolation($constraint->message);
		// }
	}
}