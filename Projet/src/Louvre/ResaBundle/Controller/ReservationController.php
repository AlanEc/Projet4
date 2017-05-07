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

          $dateVisite = $commande->getJourVisite();

          $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('LouvreResaBundle:Commande')
          ;

          /* Verification - Possibilité de reserver un billet journée */
          $TypeBillet = $commande->getTypeBillet();

          if ($TypeBillet == 'Billet journée') {
            $commande->heureReservation();
            if ($commande->getHeure() > 18 ) {
                   return $this->render('LouvreResaBundle:Reservation:billetJournee.html.twig');
            }
          }
 
         /* Verification - Nombre billet vendu */
          $listeCommande = $repository->findBy(
          array('jourVisite' => $dateVisite)
          );

          $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('LouvreResaBundle:Billet')
          ;

          $id = $commande->getId();
          $totalNombreBillet = 0;

          foreach ($listeCommande as $reservation) {
            $listeBillet = $repository->findBy(
            array('commande' => $reservation->getId())
            );
            $nombreBillet = count($listeBillet);
            $totalNombreBillet += $nombreBillet;
          }

          $jourVisite = $commande->getJourVisite();
          $formatJourVisite = $jourVisite->format('Y-m-d H:i:s');
          $date = \DateTime::createFromFormat('Y-m-d H:i:s', $formatJourVisite);
          $date = $date->format('Y-m-d');
       
          if ($totalNombreBillet >= 1000) {

          return $this->render('LouvreResaBundle:Reservation:maxBillet.html.twig', array(
          'date' => $date));
          }

          else {
          $em->persist($commande);

          $em->flush();
          return $this->redirectToRoute('louvre_prix_billet', array('id' => $commande->getId()));
        }

        }
      }

      return $this->render('LouvreResaBundle:Reservation:index.html.twig', array(
      'form' => $formBuilder->createView(),
      ));
    }

    public function prixAction($id) {

      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('LouvreResaBundle:Billet')
      ;

      /* Calcul du prix */
       $listeBillets = $repository->findBy(
        array('commande' => $id)
      );
      $commande = new Commande();
      $billet = new Billet();
      $prixTotal = 0;
      foreach ($listeBillets as $billet) {
        $billet->prix($listeBillets);
        $prix = $billet->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->persist($billet);

        $prixTotal += $prix;
      }

      $commande->setPrixTotal($prixTotal);
      
      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('LouvreResaBundle:Commande')
      ;

      $commande = $repository->findOneBy(
        array('id' => $id)
      );

      $session = new Session();
      $session->set('idCommande', $commande->getId());
      $idCommande = $session->get('idCommande');

      $commande->setPrixTotal($prixTotal);
      $em = $this->getDoctrine()->getManager();
      $em->persist($commande);
      $em->flush();

      $prixStripe = $prixTotal * 100;

      return $this->render('LouvreResaBundle:Reservation:stripe.html.twig', array(
      'prixTotal' => $prixTotal,
      'prixStripe' => $prixStripe
      ));


    }

    public function paiementAction(Request $request) {

       return $this->render('LouvreResaBundle:Reservation:stripe.html.twig');
    }

    public function stripeAction(Request $request) {

      \Stripe\Stripe::setApiKey("sk_test_vzE0pO86RkJTKOb7usTz3Z4F");
        $token = $_POST['stripeToken'];

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000,
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));

            $this->addFlash("success","Bravo ça marche !");
            return $this->redirectToRoute("louvre_paiement_reussi");

        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("order_prepare");
        }
    }


    public function finalisationAction(Request $request) {

      $session = new Session();

      $session = new Session();
      $idCommande = $session->get('idCommande');

      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('LouvreResaBundle:Commande')
      ;

      $commande = $repository->findOneBy(
      array('id' => $idCommande)
      );

      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('LouvreResaBundle:Billet')
      ;

      $billets = $repository->findBy(
      array('commande' => $idCommande)
      );

      $dateCommande = $commande->getDate();
      $formatDateCommande = $dateCommande->format('Y-m-d H:i:s');
      $date = \DateTime::createFromFormat('Y-m-d H:i:s', $formatDateCommande);
      $date = $date->format('Y-m-d H:i:s');
       
    
       $message = \Swift_Message::newInstance();
       $imgUrl = $message->embed(\Swift_Image::fromPath('http://www.amisdulouvre.fr/images/actualite/logo-louvre.jpg'));
       $message
        ->setSubject('Validation de votre commande')
        ->setFrom('projetopenc@gmail.com')
        ->setTo($commande->getEmail())
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody($this->renderView('LouvreResaBundle:Reservation:validation.html.twig', array(
      'Prix' => $commande->getPrixTotal(),
      'billets' => $billets,
      'date' => $date,
      'url'=>$imgUrl)
    ), 'text/html'
      );

      $this->get('mailer')->send($message);

       return $this->render('LouvreResaBundle:Reservation:paiement-reussi.html.twig');

    }
}

