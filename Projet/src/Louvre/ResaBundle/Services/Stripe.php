<?php
namespace Louvre\ResaBundle\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;


class Stripe
{

    private $router;

    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

  public function reglementCommande($prixStripe, $token)
  {   
      try {
      $charge = \Stripe\Charge::create(array(
      "amount" => 1000,
      "currency" => "eur",
      "source" => $token,
      "description" => "Paiement Stripe - OpenClassrooms Exemple"
      ));

      return new RedirectResponse($this->router->generate('louvre_paiement_reussi'));

    } catch(\Stripe\Error\Card $e) {
      $this->addFlash("error","Snif ça marche pas :(");
      return $this->redirectToRoute("order_prepare");
    }
  }
}