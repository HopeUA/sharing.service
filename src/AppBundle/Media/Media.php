<?php
namespace AppBundle\Media;

class Media
{
	/**
	 * @var \AppBundle\Media\Image
	 */
	private $image;

	/**
	 * @var \AppBundle\Media\Video
	 */
	private $video;

	/**
	 * @return Image
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * @param Image $image
	 */
	public function setImage( $image )
	{
		$this->image = $image;
	}

	/**
	 * @return Video
	 */
	public function getVideo()
	{
		return $this->video;
	}

	/**
	 * @param Video $video
	 */
	public function setVideo( $video )
	{
		$this->video = $video;
	}
}
