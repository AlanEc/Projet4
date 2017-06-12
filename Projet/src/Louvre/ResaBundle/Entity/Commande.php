<?php

namespace Louvre\ResaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Louvre\ResaBundle\Validator\HeureReservation;
use Louvre\ResaBundle\Validator\NombreBilletVendu;
use Louvre\ResaBundle\Validator\DateReservationImpossible;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="Louvre\ResaBundle\Repository\CommandeRepository")
 */
class Commande
{
   
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="JourVisite", type="datetime")
     * @NombreBilletVendu()
     * @DateReservationImpossible()
     */
    private $jourVisite;

    /**
     * @var string
     *
     * @ORM\Column(name="TypeBillet", type="string", length=255)
     */
    private $typeBillet;

    /**
     * @ORM\OneToMany(targetEntity="Louvre\ResaBundle\Entity\Billet", mappedBy="commande", cascade={"persist"})
     */
    private $billets;

    /**
     * @var \ int
     * @ORM\Column(name="PrixTotal", type="integer", nullable=true)
     */
    private $prixTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="EtatCommande", type="string", nullable=true)
     */
    private $etatCommande;

    /**
     * @var string
     *
     * @ORM\Column(name="Pays", type="string", nullable=true)
     */
    private $pays;

    /**
     * @var \ string
     *
     * @ORM\Column(name="Email", type="string", nullable=false)
     * @Assert\Email()
     */
    private $email;

    public function __construct() 
    {
        $this->date = new \Datetime();
        $this->billets   = new ArrayCollection();
    }

    /**
     * @Assert\Callback
     */
    public function isContentValid(ExecutionContextInterface $context)
    {
    if ($this->getTypeBillet() == 'Billet journée') {

        $dateCommandeEffectue = new \DateTime;

            if ($this->getJourVisite()->format('Y-m-d') == $dateCommandeEffectue->format('Y-m-d')) {

                if ($dateCommandeEffectue->format('H') > 15) {
                    $context
                    ->buildViolation('Il est trop tard pour réserver un billet journée !') 
                    ->atPath('content')                                                   
                    ->addViolation() 
                  ;
                }
            }
        }
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param int
     *
     * @return Commande
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set jourVisite
     *
     * @param \DateTime $jourVisite
     *
     * @return Commande
     */
    public function setJourVisite($jourVisite)
    {
        $this->jourVisite = $jourVisite;

        return $this;
    }

    /**
     * Get jourVisite
     *
     * @return \DateTime
     */
    public function getJourVisite()
    {
        return $this->jourVisite;
    }

    /**
     * Set typeBillet
     *
     * @param string $typeBillet
     *
     * @return Commande
     */
    public function setTypeBillet($typeBillet)
    {
        $this->typeBillet = $typeBillet;

        return $this;
    }

    /**
     * Get typeBillet
     *
     * @return string
     */
    public function getTypeBillet()
    {
        return $this->typeBillet;
    }

    /**
     * Set etatCommande
     *
     * @param string 
     *
     * @return Commande
     */
    public function setEtatCommande($etatCommande)
    {
        $this->etatCommande = $etatCommande;

        return $this;
    }

    /**
     * Get etatCommande
     *
     * @return string
     */
    public function getEtatCommande()
    {
        return $this->etatCommande;
    }


    /**
     * Set nombreBillet
     *
     * @param string $nombreBillet
     *
     * @return Commande
     */
    public function setNombreBillet($nombreBillet)
    {
        $this->nombreBillet = $nombreBillet;

        return $this;
    }

    /**
     * Get nombreBillet
     *
     * @return int
     */
    public function getNombreBillet()
    {
        return $this->nombreBillet;
    }

    /**
     * @param Billet $billet
     */
    public function addBillet(Billet $billet)
    {
        $this->billets[] = $billet;
    }

    /**
     * @param Billet $billet
     */
    public function removeBillet(Billet $billet)
    {
        $this->billets->removeElement($billet);
    }


    /**
     * @return ArrayCollection
     */
    public function getBillets()
    {
        return $this->billets;
    }


    /**
     * @param ArrayCollection $billets
     */
    public function setBillets($billets)
    {
        $this->billets = $billets;
        return $this;
    }
   
    /**
     * Set prixTotal
     *
     * @param integer $prixTotal
     *
     * @return Commande
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal
     *
     * @return integer
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Commande
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Commande
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
