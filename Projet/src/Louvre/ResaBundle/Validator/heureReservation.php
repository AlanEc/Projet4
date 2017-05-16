<?php
namespace Louvre\ResaBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class heureReservation extends Constraint
{
	public $message = "Il est trop tard pour réserver un billet journée!";
}