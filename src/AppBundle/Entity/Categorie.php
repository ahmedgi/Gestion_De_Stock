<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Creation_Date", type="datetimetz")
     */
    private $Creation_Date;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="Categorie")
     */
    private $Articles;

    public function __construct()
    {
        $this->Articles = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Categorie
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Categorie
     */
    public function setCreationDate($creationDate)
    {
        $this->Creation_Date = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->Creation_Date;
    }

    /**
     * Add article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Categorie
     */
    public function addArticle(\AppBundle\Entity\Article $article)
    {
        $this->Articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \AppBundle\Entity\Article $article
     */
    public function removeArticle(\AppBundle\Entity\Article $article)
    {
        $this->Articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->Articles;
    }
}
