<?php
namespace AppBundle\Media;

use AppBundle\Media\Source\Source;

class Video
{
	/**
	 * @var \AppBundle\Media\Source\Source[]
	 */
	private $sources = [];

	public function addSource(Source $source)
	{
		$this->sources[$source->getName()] = $source;
	}

    public function getSources()
    {
        return $this->sources;
    }
}