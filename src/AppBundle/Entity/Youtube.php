<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\YoutubeRepository")
 * @ORM\Table(name="youtube")
 */
class Youtube
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\OneToOne(targetEntity="Episode")
     */
    protected $video;
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $link;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $published = false;

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
     * Set link
     *
     * @param string $link
     * @return Youtube
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set video
     *
     * @param \AppBundle\Entity\Episode $video
     * @return Youtube
     */
    public function setVideo(\AppBundle\Entity\Episode $video = null)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return \AppBundle\Entity\Episode
     */
    public function getVideo()
    {
        return $this->video;
    }
    /**
     * Set published
     *
     * @param boolean $published
     * @return Youtube
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }
}
