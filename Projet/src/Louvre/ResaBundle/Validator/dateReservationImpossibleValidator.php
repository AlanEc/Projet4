<?php

namespace Louvre\ResaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Session\Session;

class DateReservationImpossibleValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$dateResa = $value->format('Y-m-d');
		$dateFormat = \DateTime::createFromFormat('Y-m-d', $dateResa);
		$dateFormatResaAnnee = $dateFormat->format('Y');
		$dateFormatResaMois = $dateFormat->format('m');
		$dateFormatResaJour = $dateFormat->format('d');
		$datef = $dateFormatResaAnnee.'/'.$dateFormatResaMois.'/'.$dateFormatResaJour; //format date
		$verifJour = date('w', strtotime($datef));
		$verifJourAnnee = date('z', strtotime($datef));

		/*Recuperation date d'aujourd'hui puis création format */
		$dateAujourdhui = new \DateTime();
		$dateAujourdhuiFormat =  $dateAujourdhui->format('Y-m-d');
		$dateFormat2 = \DateTime::createFromFormat('Y-m-d', $dateAujourdhuiFormat);
		$dateFormatResaAnnee2 = $dateFormat2->format('Y');
		$dateFormatResaMois2 = $dateFormat2->format('m');
		$dateFormatResaJour2 = $dateFormat2->format('d');
		$dateFormatAjourdhui = $dateFormatResaAnnee2.'/'.$dateFormatResaMois2.'/'.$dateFormatResaJour2;
		$verifAujourdhui = date('z', strtotime($dateFormatAjourdhui));

		/* Suppression dimanche */
		if ($verifJour == '0') {
			$this->context->addViolation($constraint->message);
		}

		/* Suppression des jours fériés */
		elseif ($verifJourAnnee == '358' or $verifJourAnnee == '304' or $verifJourAnnee == '121') {
			$this->context->addViolation($constraint->message);
		}

		/*Supprimer possibilité de reserver à une date inférieur à celle d'aujourd'hui*/
		elseif ($verifAujourdhui > $verifJourAnnee ) {
			$this->context->addViolation($constraint->message);
		}
	}
}