<?php
namespace AppBundle\Media;

class Image
{
	/**
	 * @var string  Url to the image
	 */
	private $url;

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
}
