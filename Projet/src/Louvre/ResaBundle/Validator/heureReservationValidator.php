<?php

namespace Louvre\ResaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Session\Session;

class HeureReservationValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$session = new Session();
		$typebillet = $session->get('typeBillet');

		if ($typebillet == 'Billet journée') {
		$dateReservation = $value;
		$dateCommandeEffectue = new \DateTime;
		$dateFormat = $dateReservation->format('Y-m-d');
		$date = \DateTime::createFromFormat('Y-m-d', $dateFormat);
		$date = $date->format('Y-m-d');

		$dateFormat1 = $dateCommandeEffectue->format('Y-m-d');
		$date1 = \DateTime::createFromFormat('Y-m-d', $dateFormat1);
		$date1 = $date1->format('Y-m-d');

			if ($date == $date1) {
				$heure = new \DateTime;
				$dateFormat = $heure->format('Y-m-d H:i:s');
				$dateheure = \DateTime::createFromFormat('Y-m-d H:i:s', $dateFormat);
				$dateheure = $dateheure->format('H');

				if ($dateheure > 1) {
					$this->context->addViolation($constraint->message);
				}
			}
		}
	}
}