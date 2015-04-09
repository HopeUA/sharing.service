<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 * @ORM\Table(name="categories")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Serializer\Expose()
     */
    private $uid;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     */
    private $title_ru;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     */
    private $title_ua;
    /**
     * @ORM\OneToMany(targetEntity="Show", mappedBy="category")
     */
    private $shows;
    /**
     * @ORM\Column(type="integer")
     */
    protected $sort;
    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Expose()
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $modified;

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

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Category
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return Category
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set title_ru
     *
     * @param string $titleRu
     * @return Category
     */
    public function setTitleRu($titleRu)
    {
        $this->title_ru = $titleRu;

        return $this;
    }

    /**
     * Get title_ru
     *
     * @return string 
     */
    public function getTitleRu()
    {
        return $this->title_ru;
    }

    /**
     * Set title_ua
     *
     * @param string $titleUa
     * @return Category
     */
    public function setTitleUa($titleUa)
    {
        $this->title_ua = $titleUa;

        return $this;
    }

    /**
     * Get title_ua
     *
     * @return string 
     */
    public function getTitleUa()
    {
        return $this->title_ua;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Category
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }
}
