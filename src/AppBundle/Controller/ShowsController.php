<?php
namespace AppBundle\Controller;

use AppBundle\Helper\ListParameters;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EpisodesController
 */
class ShowsController extends AppController
{
	/**
	 * @var \AppBundle\Entity\ShowRepository
	 */
	private $showRepo;

	public function init()
	{
		$this->showRepo = $this->getDoctrine()->getRepository('AppBundle:Show');
		$this->showRepo->setPaginator($this->get( 'knp_paginator' ));
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getShowsAction(Request $request)
	{
		$params = ListParameters::createFromRequest($request);
		$shows  = $this->showRepo->getList($params);

		if (count($shows) == 0) {
			throw new NotFoundHttpException('There is no shows, matching your request');
		}

		return $shows;
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getShowAction($code)
	{
		$show = $this->showRepo->getOne($code);

		if ($show === null) {
			throw new NotFoundHttpException('Show not found');
		}

		return $show;
	}
}