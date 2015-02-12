<?php
namespace AppBundle\Controller;

use AppBundle\Helper\ListParameters;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EpisodesController
 */
class CategoriesController extends AppController
{
	/**
	 * @var \AppBundle\Entity\CategoryRepository
	 */
	private $catRepo;

	public function init()
	{
		$this->catRepo = $this->getDoctrine()->getRepository('AppBundle:Category');
		$this->catRepo->setPaginator($this->get( 'knp_paginator' ));
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getCategoriesAction(Request $request)
	{
		$params = ListParameters::createFromRequest($request);
		$categories  = $this->catRepo->getList($params);

		if (count($categories) == 0) {
			throw new NotFoundHttpException('There is no categories, matching your request');
		}

		return $categories;
	}

	/**
	 * @param int $id
	 *
	 * @return array
	 *
	 * @View()
	 */
	public function getCategoryAction($id)
	{
		$category = $this->catRepo->getOne($id);

		if ($category === null) {
			throw new NotFoundHttpException('Category not found');
		}

		return $category;
	}
}