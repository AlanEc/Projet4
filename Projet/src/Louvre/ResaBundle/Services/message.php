<?php
namespace Louvre\ResaBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Louvre\ResaBundle\Entity\Commande;
use Louvre\ResaBundle\Entity\Billet;

class message
{

  private $mailer;
  private $templating;


  public function __construct(\Swift_Mailer $mailer, $templating)
  {
    $this->mailer = $mailer;
    $this->templating = $templating;
  }

  public function constructionMessage()
  {

    $session = new Session();
    $idCommande = $session->get('idCommande');
 
    $repository = $this
    ->em->getRepository('LouvreResaBundle:Commande')
    ;

    $commande = $repository->findOneBy(
    array('id' => $idCommande)
    );

    $repository = $this
    ->em->getRepository('LouvreResaBundle:Billet')
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
    ->setBody($this->templating->render('LouvreResaBundle:Reservation:validation.html.twig', array(
    'Prix' => $commande->getPrixTotal(),
    'billets' => $billets,
    'date' => $date,
    'url'=>$imgUrl)
    ), 'text/html'
    );

    $this->mailer->send($message);
  }
}