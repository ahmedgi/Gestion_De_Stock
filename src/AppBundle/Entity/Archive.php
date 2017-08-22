<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Article;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Archive
 *
 * @ORM\Table(name="archive")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArchiveRepository")
 */
class Archive
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
     * @ORM\Column(name="Nature", type="string", length=255)
     */
    private $nature;

    /**
     * @var int
     *
     * @ORM\Column(name="Numero", type="integer")
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="Archives")
     * @ORM\JoinColumn(name="Article_id", referencedColumnName="id")
     */
    private $Article;


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
     * Set nature
     *
     * @param string $nature
     *
     * @return Archive
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return string
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Archive
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Archive
     */
    public function setArticle(\AppBundle\Entity\Article $article = null)
    {
        $this->Article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->Article;
    }
}
