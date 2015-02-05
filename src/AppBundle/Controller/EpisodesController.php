<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * Class EpisodesController
 */
class EpisodesController extends Controller
{
	/**
	 * @var \AppBundle\Entity\EpisodeRepository
	 */
	private $episodeRepo;

	public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->init();
    }

	public function init()
	{
		$this->episodeRepo = $this->getDoctrine()->getRepository('AppBundle:Episode');
	}

	/**
	 * @return array
	 * @View()
	 */
	public function getEpisodesAction()
	{
		$episodes = $this->episodeRepo->getAll();

		return ['episodes' => $episodes];
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

		return $episode;
	}
}