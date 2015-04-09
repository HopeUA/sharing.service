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
	/**
	 * @var \AppBundle\Entity\ShowRepository
	 */
	private $showRepo;

	public function init()
	{
		$this->seasonRepo = $this->getDoctrine()->getRepository('AppBundle:Season');
		$this->seasonRepo->setPaginator($this->get( 'knp_paginator' ));

        $this->showRepo = $this->getDoctrine()->getRepository('AppBundle:Show');
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
        $show = $this->showRepo->getOne($show);
        if (null === $show) {
            throw new NotFoundHttpException('Show not found');
        }

		$params = ListParameters::createFromRequest($request);
        $params->set('showId', $show->getId());
		$seasons  = $this->seasonRepo->getList($params);

		if (count($seasons) == 0) {
			throw new NotFoundHttpException('There is no seasons, matching your request');
		}

		return $seasons;
	}

	/**
     * @param string $show
	 * @param string $uid
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getSeasonAction($show, $uid)
	{
        $show = $this->showRepo->getOne($show);
        if (null === $show) {
            throw new NotFoundHttpException('Show not found');
        }

		$season = $this->seasonRepo->getOne($show, $uid);

		if ($season === null) {
			throw new NotFoundHttpException('Season not found');
		}

		return $season;
	}
}