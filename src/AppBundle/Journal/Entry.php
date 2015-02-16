<?php
namespace AppBundle\Journal;

abstract class Entry
{
    /**
     * @var \DateTime  Entry date
     */
    protected $moment;
    /**
     * @var mixed  Entry data
     */
    protected $data = [];
    /**
     * @var string  UID
     */
    protected $uid;

    public function __construct()
    {
        $this->moment = new \DateTime();
    }

    abstract public function setData($data);

    /**
     * @return string  Entry type
     */
    public function getType()
    {
        $class = array_pop(explode('\\', get_called_class()));
        $type  = ltrim(strtolower(preg_replace('/[A-Z]/', '.$0', $class)), '.');

        return $type;
    }

    /**
     * @return \DateTime
     */
    public function getMoment()
    {
        return $this->moment;
    }

    /**
     * @return mixed  Data object
     */
    public function getData()
    {
        return $this->data;
    }

    public function getUid()
    {
        return $this->uid;
    }
}
