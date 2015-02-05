<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * Class EpisodesController
 */
class ShowsController extends Controller
{
	/**
	 * @var \AppBundle\Entity\ShowRepository
	 */
	private $showRepo;

	public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->init();
    }

	public function init()
	{
		$this->showRepo = $this->getDoctrine()->getRepository('AppBundle:Show');
	}

	/**
	 * @return array
	 * @View()
	 */
	public function getShowsAction()
	{
		$shows = $this->showRepo->getAll();

		return ['shows' => $shows];
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

		return $show;
	}
}