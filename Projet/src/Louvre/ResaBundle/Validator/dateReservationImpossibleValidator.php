<?php

namespace Louvre\ResaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Session\Session;

class DateReservationImpossibleValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$dateCommande = $value;
		$formatDateCommande = $dateCommande->format('Y-m-d H:i:s');
		$date = \DateTime::createFromFormat('Y-m-d H:i:s', $formatDateCommande);
		$date = $date->format('Y-m-d H:i:s');
		$str = $date;
		$time = strtotime($str);
		$newDate = date("D", $time);	
	}
}