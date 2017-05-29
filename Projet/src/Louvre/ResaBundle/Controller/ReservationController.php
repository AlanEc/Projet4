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

class ReservationController extends Controller
{
  public function indexAction(Request $request) {

    $commande = new Commande();
    $formBuilder = $this->get('form.factory')->create(CommandeType::class, $commande);

    if ($request->isMethod('POST')) {
      $formBuilder->handleRequest($request);

      if ($formBuilder->isValid()) {
        $em = $this->getDoctrine()->getManager();

        foreach ($commande->getBillets() as $billet) {
          $billet->setCommande($commande);       
        }

        /* Verification - Type de billet envoyé au validator heureReservation */
        $typeBillet = $commande->getTypeBillet();
        $session = new Session();
        $session->set('typeBillet', $typeBillet);
        
        // var_dump($commande->getBillets());
        $calculPrix = $this->container->get('louvre_resa.CalculPrix');
        $prixTotal = $calculPrix->calcul($commande);

        /*Permet de récupérer la commande dans le contrôleur finalisationAction */
        $commande->setPrixTotal($prixTotal);
        $session->set('commande', $commande);
        $em->persist($commande);
        $em->flush();

        /*Permet de récupérer la date de la commande dans le contrôleur finalisationAction */
        // $session->set('idCommande', $commande->getId());
     
        $prixStripe = $prixTotal * 100;

        return $this->redirectToRoute('louvre_prix_billet', array('id' => $commande->getId(),
          'prixStripe' => $prixStripe,
          'prixTotal' => $prixTotal
          ));
      }}

    return $this->render('LouvreResaBundle:Reservation:index.html.twig', array(
    'form' => $formBuilder->createView()
    ));
    }

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

    \Stripe\Stripe::setApiKey($this->container->getParameter('api_stripe'));
    $token = $_POST['stripeToken'];

    $reglement = $this->container->get('louvre_resa.Stripe');
    return $reglement->reglementCommande($prixStripe, $token);
  }

  public function finalisationAction(Request $request) {

    $session = new Session();
    $commande = $session->get('commande');

    /*Modifier Etat Commande */
    $commandePaye = $commande->setEtatCommande('Payé');
    $em = $this->getDoctrine()->getManager();
    $em->persist($commande);
    $em->flush();

    $message = $this->container->get('louvre_resa.Message');
    $message->constructionMessage($commande);

    return $this->render('LouvreResaBundle:Reservation:paiement-reussi.html.twig');
  }
}


