<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Categorie;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
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
     * @ORM\Column(name="Lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="Designation", type="string", length=255)
     */
    private $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="Marque", type="string", length=255)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="Modele", type="string", length=255)
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="NumSerie", type="string", length=255)
     */
    private $numSerie;

    /**
     * @var string
     *
     * @ORM\Column(name="Societe", type="string", length=255)
     */
    private $societe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateService", type="datetimetz")
     */
    private $dateService;
    
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $BonLivraison;

    /**
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="Articles")
     * @ORM\JoinColumn(name="Categorie_id", referencedColumnName="id")
     */
    private $Categorie;


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
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Article
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return Article
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set marque
     *
     * @param string $marque
     *
     * @return Article
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set modele
     *
     * @param string $modele
     *
     * @return Article
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set numSerie
     *
     * @param string $numSerie
     *
     * @return Article
     */
    public function setNumSerie($numSerie)
    {
        $this->numSerie = $numSerie;

        return $this;
    }

    /**
     * Get numSerie
     *
     * @return string
     */
    public function getNumSerie()
    {
        return $this->numSerie;
    }

    /**
     * Set societe
     *
     * @param string $societe
     *
     * @return Article
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set dateService
     *
     * @param \DateTime $dateService
     *
     * @return Article
     */
    public function setDateService($dateService)
    {
        $this->dateService = $dateService;

        return $this;
    }

    /**
     * Get dateService
     *
     * @return \DateTime
     */
    public function getDateService()
    {
        return $this->dateService;
    }

    /**
     * Set bonLivraison
     *
     * @param string $bonLivraison
     *
     * @return Article
     */
    public function setBonLivraison($bonLivraison)
    {
        $this->BonLivraison = $bonLivraison;

        return $this;
    }

    /**
     * Get bonLivraison
     *
     * @return string
     */
    public function getBonLivraison()
    {
        return $this->BonLivraison;
    }

    /**
     * Set categorie
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return Article
     */
    public function setCategorie($categorie = null)
    {
        $this->Categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->Categorie;
    }
}
