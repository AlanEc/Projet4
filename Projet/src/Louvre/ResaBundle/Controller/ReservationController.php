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
        $em->persist($commande);
        $em->flush();

        $calculPrix = $this->container->get('louvre_resa.calculPrix');
        $prixTotal = $calculPrix->calcul($commande->getId());
        $commande->setPrixTotal($prixTotal);
        $em->persist($commande);
        $em->flush();

        /*Permet de récupérer la date de la commande dans le contrôleur finalisationAction */
        $session->set('idCommande', $commande->getId());
     
        $prixStripe = $prixTotal * 100;

        // return $this->redirectToRoute('louvre_prix_billet', array('id' => $commande->getId(),
        //   'prixStripe' => $prixStripe,
        //   'prixTotal' => $prixTotal
        //   ));
      }}

    return $this->render('LouvreResaBundle:Reservation:index.html.twig', array(
    'form' => $formBuilder->createView()
    ));
    }

  // public function prixAction($id, Request $request) {

  //   $prixTotal = $request->query->get('prixTotal');
  //   $prixStripe = $request->query->get('prixStripe');

  //   return $this->render('LouvreResaBundle:Reservation:stripe.html.twig', array(
  //   'prixTotal' => $prixTotal,
  //   'prixStripe' => $prixStripe
  //   ));
  // }

  // public function paiementAction(Request $request) {

  //   return $this->render('LouvreResaBundle:Reservation:stripe.html.twig');
  // }

  // public function stripeAction(Request $request) {

  //   \Stripe\Stripe::setApiKey($this->container->getParameter('api_stripe'));
  //   $token = $_POST['stripeToken'];

  //   try {
  //     $charge = \Stripe\Charge::create(array(
  //     "amount" => 1000,
  //     "currency" => "eur",
  //     "source" => $token,
  //     "description" => "Paiement Stripe - OpenClassrooms Exemple"
  //     ));

  //     $this->addFlash("success","Bravo ça marche !");
  //     return $this->redirectToRoute("louvre_paiement_reussi");

  //   } catch(\Stripe\Error\Card $e) {
  //     $this->addFlash("error","Snif ça marche pas :(");
  //     return $this->redirectToRoute("order_prepare");
  //   }
  // }

  // public function finalisationAction(Request $request) {

  //   $message = $this->container->get('louvre_resa.message');

  //   // $session = new Session();
  //   // $idCommande = $session->get('idCommande');
 
  //   // $repository = $this
  //   // ->getDoctrine()
  //   // ->getManager()
  //   // ->getRepository('LouvreResaBundle:Commande')
  //   // ;

  //   // $commande = $repository->findOneBy(
  //   // array('id' => $idCommande)
  //   // );

  //   // $repository = $this
  //   // ->getDoctrine()
  //   // ->getManager()
  //   // ->getRepository('LouvreResaBundle:Billet')
  //   // ;

  //   // $billets = $repository->findBy(
  //   // array('commande' => $idCommande)
  //   // );

  //   // $dateCommande = $commande->getDate();
  //   // $formatDateCommande = $dateCommande->format('Y-m-d H:i:s');
  //   // $date = \DateTime::createFromFormat('Y-m-d H:i:s', $formatDateCommande);
  //   // $date = $date->format('Y-m-d H:i:s');

  //   // $message = \Swift_Message::newInstance();
  //   // $imgUrl = $message->embed(\Swift_Image::fromPath('http://www.amisdulouvre.fr/images/actualite/logo-louvre.jpg'));
  //   // $message
  //   // ->setSubject('Validation de votre commande')
  //   // ->setFrom('projetopenc@gmail.com')
  //   // ->setTo($commande->getEmail())
  //   // ->setCharset('utf-8')
  //   // ->setContentType('text/html')
  //   // ->setBody($this->renderView('LouvreResaBundle:Reservation:validation.html.twig', array(
  //   // 'Prix' => $commande->getPrixTotal(),
  //   // 'billets' => $billets,
  //   // 'date' => $date,
  //   // 'url'=>$imgUrl)
  //   // ), 'text/html'
  //   // );

  //   // $this->get('mailer')->send($message);
  //   return $this->render('LouvreResaBundle:Reservation:paiement-reussi.html.twig');
  // }
}


