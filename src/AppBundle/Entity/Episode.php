<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EpisodeRepository")
 * @ORM\EntityListeners({"AppBundle\Entity\EpisodeListener"})
 * @ORM\Table(name="shared_video", uniqueConstraints={@UniqueConstraint(name="code_idx", columns={"code"})})
 */
class Episode
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=9)
     */
    protected $code;
    /**
     * @ORM\ManyToOne(targetEntity="Show", inversedBy="videos")
     * @ORM\JoinColumn(name="program", referencedColumnName="id")
     */
    protected $program;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    /**
     * @ORM\Column(type="text", name="`desc`")
     */
    protected $desc;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $tags = '';
    /**
     * @ORM\Column(type="datetime")
     */
    protected $publish;
    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $hd;
    /**
     * @ORM\OneToOne(targetEntity="Youtube", mappedBy="video")
     */
    protected $youtube;
    /**
     * @ORM\Column(type="smallint")
     */
    protected $trash = 0;

    protected $syncFields = array(
                'title',
                'desc',
                'tags',
                'publish'
              );
    /**
     * Флаг обновления записи
     *
     * @var string
     */
    protected $status;

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
     * @return Episode
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
     * @return Episode
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
     * Set desc
     *
     * @param string $desc
     * @return Episode
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    
        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Episode
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set publish
     *
     * @param \DateTime $publish
     * @return Episode
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
    
        return $this;
    }

    /**
     * Get publish
     *
     * @return \DateTime 
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Episode
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set hd
     *
     * @param integer $hd
     * @return Episode
     */
    public function setHd($hd)
    {
        $this->hd = $hd;
    
        return $this;
    }

    /**
     * Get hd
     *
     * @return integer 
     */
    public function getHd()
    {
        return $this->hd;
    }

    /**
     * Set youtube
     *
     * @param \AppBundle\Entity\Youtube $youtube
     * @return Episode
     */
    public function setYoutube(\AppBundle\Entity\Youtube $youtube = null)
    {
        $this->youtube = $youtube;
    
        return $this;
    }

    /**
     * Get youtube
     *
     * @return \AppBundle\Entity\Youtube
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set program
     *
     * @param \AppBundle\Entity\Show $program
     * @return Episode
     */
    public function setProgram(\AppBundle\Entity\Show $program = null)
    {
        $this->program = $program;
    
        return $this;
    }

    /**
     * Get program
     *
     * @return \AppBundle\Entity\Show
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set trash
     *
     * @param integer $trash
     * @return Episode
     */
    public function setTrash($trash)
    {
        $this->trash = $trash;

        return $this;
    }

    /**
     * Get trash
     *
     * @return integer 
     */
    public function getTrash()
    {
        return $this->trash;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
