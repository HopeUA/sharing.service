<?php
namespace AppBundle\Entity;

use AppBundle\Helper\Sortable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 * @ORM\Table(name="categories")
 *
 * @Serializer\ExclusionPolicy("all")
 * Serializer\AccessorOrder("custom", custom = {"code", "title", "program_name", "desc", "tags", "media"})
 */
class Category
{
    use Sortable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     */
    private $title;
    /**
     * @ORM\OneToMany(targetEntity="Show", mappedBy="category")
     */
    private $shows;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shows = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add shows
     *
     * @param \AppBundle\Entity\Show $shows
     * @return Category
     */
    public function addShow(\AppBundle\Entity\Show $shows)
    {
        $this->shows[] = $shows;

        return $this;
    }

    /**
     * Remove shows
     *
     * @param \AppBundle\Entity\Show $shows
     */
    public function removeShow(\AppBundle\Entity\Show $shows)
    {
        $this->shows->removeElement($shows);
    }

    /**
     * Get shows
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShows()
    {
        return $this->shows;
    }
}
