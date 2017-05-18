<?php
namespace Louvre\ResaBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Louvre\ResaBundle\Entity\Commande;
use Louvre\ResaBundle\Entity\Billet;

class Message
{

  private $mailer;
  private $templating;


  public function __construct(\Swift_Mailer $mailer, $templating)
  {
    $this->mailer = $mailer;
    $this->templating = $templating;
  }

  public function constructionMessage($commande)
  {

    $billets = $commande->getBillets();

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