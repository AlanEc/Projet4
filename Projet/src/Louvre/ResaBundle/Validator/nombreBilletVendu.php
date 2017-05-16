<?php
namespace Louvre\ResaBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class nombreBilletVendu extends Constraint
{
	public $message = "Le nombre maximum de billet a été atteint pour cette journée";

	public function validatedBy()
  {
    return 'Louvre_resa_nombreBilletVendu'; 
  }
}