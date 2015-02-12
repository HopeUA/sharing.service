<?php
namespace AppBundle\Controller;

use AppBundle\Helper\ListParameters;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SeasonsController
 */
class SeasonsController extends AppController
{
	/**
	 * @var \AppBundle\Entity\SeasonRepository
	 */
	private $seasonRepo;

	public function init()
	{
		$this->seasonRepo = $this->getDoctrine()->getRepository('AppBundle:Season');
		$this->seasonRepo->setPaginator($this->get( 'knp_paginator' ));
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getSeasonsAction($show, Request $request)
	{
		$params = ListParameters::createFromRequest($request);
		$seasons  = $this->seasonRepo->getList($params);

		if (count($seasons) == 0) {
			throw new NotFoundHttpException('There is no seasons, matching your request');
		}

		return $seasons;
	}

	/**
	 * @param string $uid
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getSeasonAction($show, $uid)
	{
		$season = $this->seasonRepo->getOne($uid);

		if ($season === null) {
			throw new NotFoundHttpException('Season not found');
		}

		return $season;
	}
}