<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ShowRepository")
 * @ORM\Table(name="shared_program", uniqueConstraints={@ORM\UniqueConstraint(name="code_idx", columns={"code"})})
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessorOrder("custom", custom = {"code", "title", "category", "media"})
 */
class Show
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=4)
     *
     * @Serializer\Expose()
     */
    protected $code;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     */
    protected $title;
    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="program")
     */
    protected $videos;
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="shows")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    protected $category;
    /**
     * @ORM\OneToMany(targetEntity="Season", mappedBy="show")
     */
    protected $seasons;
    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Expose()
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $modified;
    
    /**
     * @Serializer\Expose()
     */
    protected $media;

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia( $media )
    {
        $this->media = $media;
    }

    public function __construct()
    {
        $this->videos = new ArrayCollection();
    }

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
     * Set code
     *
     * @param string $code
     * @return Show
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Show
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
     * Add videos
     *
     * @param \AppBundle\Entity\Episode $videos
     * @return Show
     */
    public function addVideo(\AppBundle\Entity\Episode $videos)
    {
        $this->videos[] = $videos;
    
        return $this;
    }

    /**
     * Remove videos
     *
     * @param \AppBundle\Entity\Episode $videos
     */
    public function removeVideo(\AppBundle\Entity\Episode $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Show
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("category")
     *
     * @return string
     */
    public function getCategoryId()
    {
        $id = 0;
        if ($this->getCategory()) {
            $id = $this->getCategory()->getId();
        }
        return $id;
    }

    /**
     * Add seasons
     *
     * @param \AppBundle\Entity\Season $seasons
     * @return Show
     */
    public function addSeason(\AppBundle\Entity\Season $seasons)
    {
        $this->seasons[] = $seasons;

        return $this;
    }

    /**
     * Remove seasons
     *
     * @param \AppBundle\Entity\Season $seasons
     */
    public function removeSeason(\AppBundle\Entity\Season $seasons)
    {
        $this->seasons->removeElement($seasons);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Show
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
