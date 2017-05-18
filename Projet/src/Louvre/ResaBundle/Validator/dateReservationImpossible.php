<?php
namespace Louvre\ResaBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateReservationImpossible extends Constraint
{
	public $message = "Le musée est fermé";
}