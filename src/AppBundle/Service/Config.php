<?php
namespace AppBundle\Service;

use Symfony\Component\Yaml\Yaml;

class Config
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function load()
    {
        if (!file_exists($this->file)) {
            return [
                'process.id'    => 0,
                'process.start' => 0,
                'process.end'   => 0,
                'entry.id'      => 0,
            ];
        }

        return Yaml::parse(file_get_contents($this->file));
    }

    public function save($data)
    {
        file_put_contents($this->file, Yaml::dump($data));
    }
}