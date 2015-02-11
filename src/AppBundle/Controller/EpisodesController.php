<?php
namespace AppBundle\Controller;

use AppBundle\Helper\ListParameters;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EpisodesController
 */
class EpisodesController extends AppController
{
	/**
	 * @var \AppBundle\Entity\EpisodeRepository
	 */
	private $episodeRepo;

	public function init()
	{
		$this->episodeRepo = $this->getDoctrine()->getRepository('AppBundle:Episode');
		$this->episodeRepo->setPaginator($this->get( 'knp_paginator' ));
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getEpisodesAction(Request $request)
	{
		$params   = ListParameters::createFromRequest($request);
		$episodes = $this->episodeRepo->getList($params);

		if (count($episodes) == 0) {
			throw new NotFoundHttpException('There is no episodes, matching your request');
		}

		return $episodes;
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getEpisodeAction($code)
	{
		$episode = $this->episodeRepo->getOne($code);

		if ($episode === null) {
			throw new NotFoundHttpException('Episode not found');
		}

		return $episode;
	}
}