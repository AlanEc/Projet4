<?php

namespace Louvre\ResaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\DateTime()
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
     *
     * @ORM\Column(name="PrixTotal", type="integer", nullable=true)
     */
    private $prixTotal;

    /**
     * @var \ string
     *
     * @ORM\Column(name="Email", type="string", nullable=false)
     */
    private $email;

    private $heure;

    public function __construct() 
    {
        $this->date = new \Datetime();
        $this->billets   = new ArrayCollection();
    }

    public function heureReservation() 
    {
        /* Verification - Possibilité de reserver un billet journée */
        $dateReservation = $this->getDate();
        $dateCommandeEffectue = new \DateTime;

        $dateFormat = $dateReservation->format('Y-m-d');
        $date = \DateTime::createFromFormat('Y-m-d', $dateFormat);
        $date = $date->format('Y-m-d');

        $dateFormat1 = $dateCommandeEffectue->format('Y-m-d');
        $date1 = \DateTime::createFromFormat('Y-m-d', $dateFormat1);
        $date1 = $date1->format('Y-m-d');

        if ($date == $date1) {
            $dateFormat = $dateReservation->format('Y-m-d H:i:s');
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateFormat);
            $date = $date->format('H');

            $this->heure = $date;
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
     * Set pays
     *
     * @param string $Pays
     *
     * @return Commande
     */
    public function setPays($Pays)
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
     * Set nombreBillet
     *
     * @param integer $nombreBillet
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

    public function getHeure()
    {
        return $this->heure;
    }
}
