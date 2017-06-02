<?php

namespace Louvre\ResaBundle\Controller;

use Louvre\ResaBundle\Entity\Commande;
use Louvre\ResaBundle\Entity\Billet;
use Louvre\ResaBundle\Form\CommandeType;
use Louvre\ResaBundle\Form\BilletType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;

class PaiementController extends Controller
{
  public function prixAction($id, Request $request) {

    $prixTotal = $request->query->get('prixTotal');
    $prixStripe = $request->query->get('prixStripe');

    return $this->render('LouvreResaBundle:Reservation:stripe.html.twig', array(
    'prixTotal' => $prixTotal,
    'prixStripe' => $prixStripe
    ));
  }

  public function paiementAction(Request $request) {

    return $this->render('LouvreResaBundle:Reservation:stripe.html.twig');
  }

  public function stripeAction(Request $request) {

    $session = new Session();
    $commande = $session->get('commande');
    $prix = $commande->getPrixTotal();
    $prixStripe = $prix * 100;

    \Stripe\Stripe::setApiKey($this->container->getParameter('API_KEY_STRIPE'));
    $token = $_POST['stripeToken'];

    $reglement = $this->container->get('louvre_resa.Stripe');
    return $reglement->reglementCommande($prixStripe, $token);
  }

  public function finalisationAction(Request $request) {

    $session = new Session();
    $commande = $session->get('commande');

    $repository = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('LouvreResaBundle:Commande');
    $commanderec = $repository->find($commande->getId());

    /*Modifier Etat Commande */
    $commandePaye = $commanderec->setEtatCommande('Payé');
    $em = $this->getDoctrine()->getManager();
    $em->persist($commandePaye);
    $em->flush();

    $message = $this->container->get('louvre_resa.Message');
    $message->constructionMessage($commande);

    return $this->render('LouvreResaBundle:Reservation:paiement-reussi.html.twig');
  }
}


