<?php

namespace Louvre\ResaBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="Louvre\ResaBundle\Repository\BilletRepository")
 */
class Billet
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
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     * @Assert\Length(min=3)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=255)
     * @Assert\Length(min=3)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateNaissance", type="date")
     * @Assert\DateTime()
     */
    private $dateNaissance;

    /**
     * @ORM\ManyToOne(targetEntity="Louvre\ResaBundle\Entity\Commande", inversedBy="billets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(name="TarifReduit", type="boolean")
     */
    private $tarifReduit;

    /**
     * @var \ int
     *
     * @ORM\Column(name="Prix", type="integer", nullable=true)
     */
    private $prix;

    public function prix($listeBillets)
    {
        $prixTotal = 0;

        if ($this->getTarifReduit() == 1) {
          $prix = 10;
        }

        else {
          $datetime1 = $this->getDateNaissance();
          $datetime2 = new \DateTime;
          $interval = $datetime1->diff($datetime2);

          $age = $interval->format('%y');

          if ($age > 60) {
            $prix = 12;
          }

          if ($age > 12 && $age < 60) {
            $prix = 16;
          }

          if ($age > 4 && $age < 12) {
            $prix = 8;
          }

          if ($age < 4) {
            $prix = 0;
          }
        }

        $this->setPrix($prix);

        
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Billet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Billet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Billet
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Billet
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }
     

    /**
     * @param Commande $commande
     */
    public function setCommande(Commande $commande)
    {
        $this->commande = $commande;
        return $this;
    }

    /**
     * @return Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;
        return $this;
    }

    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    
}
