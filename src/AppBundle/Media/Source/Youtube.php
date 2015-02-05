<?php
namespace AppBundle\Media\Source;

class Youtube implements Source
{
	/**
	 * @var string  Youtube internal ID
	 */
	private $id;

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'youtube';
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}
}
