<?php
namespace AppBundle\Media\Source;

class Local implements Source
{
	/**
	 * @var string  URL to video file
	 */
	private $url;

	/**
	 * @var int  Size of file in bytes
	 */
	private $size;

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'local';
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl( $url )
	{
		$this->url = $url;
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param int $size
	 */
	public function setSize( $size )
	{
		$this->size = $size;
	}
}
