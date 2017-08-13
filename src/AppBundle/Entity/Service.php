<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service
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
     * @var int
     *
     * @ORM\Column(name="Num", type="integer")
     */
    private $num;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;

        /**
     * @ORM\ManyToOne(targetEntity="Hopitale", inversedBy="Services")
     * @ORM\JoinColumn(name="Hopitale_id", referencedColumnName="id")
     */
    private $Hopitale;


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
     * Set num
     *
     * @param integer $num
     *
     * @return Service
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return int
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Service
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
     * Set hopitale
     *
     * @param \AppBundle\Entity\Hopitale $hopitale
     *
     * @return Service
     */
    public function setHopitale(\AppBundle\Entity\Hopitale $hopitale = null)
    {
        $this->Hopitale = $hopitale;

        return $this;
    }

    /**
     * Get hopitale
     *
     * @return \AppBundle\Entity\Hopitale
     */
    public function getHopitale()
    {
        return $this->Hopitale;
    }
}
