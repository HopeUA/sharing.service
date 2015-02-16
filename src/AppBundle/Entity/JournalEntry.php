<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\JournalEntryRepository")
 * @ORM\Table(name="journal")
 *
 * @Serializer\ExclusionPolicy("all")
 * Serializer\AccessorOrder("custom", custom = {"code", "title", "program_name", "desc", "tags", "media"})
 */
class JournalEntry
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=10)
     *
     * @Serializer\Expose()
     */
    protected $uid;
    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Serializer\Expose()
     */
    protected $type;
    /**
     * @ORM\Column(type="json_array")
     *
     * @Serializer\Expose()
     */
    protected $data;
    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Expose()
     */
    protected $moment;

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
     * Set uid
     *
     * @param string $uid
     * @return JournalEntry
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
     * Set type
     *
     * @param string $type
     * @return JournalEntry
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return JournalEntry
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set moment
     *
     * @param \DateTime $moment
     * @return JournalEntry
     */
    public function setMoment($moment)
    {
        $this->moment = $moment;

        return $this;
    }

    /**
     * Get moment
     *
     * @return \DateTime 
     */
    public function getMoment()
    {
        return $this->moment;
    }
}
