<?php
namespace AppBundle\Entity;

use AppBundle\Helper\ListParameters;
use AppBundle\Media\Media;
use AppBundle\Media\Image;
use AppBundle\Media\Video;
use AppBundle\Media\Source;
use AppBundle\Service\Youtube\Template;
use AppBundle\Service\Youtube\ChannelSelector;

/**
 * EpisodeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EpisodeRepository extends ResourceRepository
{
    public function getList(ListParameters $params)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from('AppBundle:Episode', 'e')
            ->orderBy('e.id', 'ASC');

        // Modified from
        $qb->where('e.modified >= :from');
        $qb->setParameter('from', $params->getFrom());

        $episodes = $this->getPaginator()->paginate($qb, $params->getPage(), $params->getLimit());

        foreach ($episodes as $episode) {
            $this->injectMedia($episode);
        }

        return $episodes;
    }

    /**
     * @param $code
     *
     * @return Episode
     */
    public function getOne($code)
    {
        /**
         * @var \AppBundle\Entity\Episode $episode
         */
        $episode = $this->findOneBy(['code' => $code]);
        if ($episode) {
            $this->injectMedia($episode);
        }

        return $episode;
    }

    /**
     * @param Episode $episode
     */
    private function injectMedia(Episode $episode)
    {
        $media = new Media();

        $image = new Image();
        $image->setUrl(sprintf('http://share.yourhope.tv/%s.jpg', $episode->getCode()));
        $media->setImage($image);

        $video = new Video();

        $localSource = new Source\Local();
        $localSource->setUrl(sprintf('http://share.yourhope.tv/%s.mov', $episode->getCode()));
        $localSource->setSize(0);

        $video->addSource($localSource);

        if ($episode->getYoutube() !== null) {
            $youtubeSource = new Source\Youtube();
            $youtubeSource->setId($episode->getYoutube()->getLink());

            $video->addSource($youtubeSource);
        }

        $media->setVideo($video);

        $episode->setMedia($media);
    }

    /**
     * @param Episode     $video
     * @param int         $compressor
     * @param string      $priority
     * @param             $mqGroup
     */
    public function compressForYoutube(Episode $video, $compressor = 1, $priority = "medium", $mqGroup, $preset)
    {
        $code        = $video->getCode();
        $programCode = $video->getProgram()->getCode();

        $compressorData = array(
            "input" => array(
                "source" => "local",
                "path"   => "/FS/archive2/$programCode/source/$code.mov"
            ),
            "output" => array(
                "source"     => "ftp",
                "connection" => "archive",
                "path"       => "/$programCode/share/$code.mov"
            ),
            "compressor" => $compressor,
            "preset"     => $preset,
            "priority"   => $priority,
        );

        /**
         * @var \AppBundle\Entity\MessageRepository $mr
         */
        $mr = $this->getEntityManager()->getRepository('AppBundle:Message');
        $mr->publish($video->getCode(), 'Compressor', $compressorData, 100, 1, $mqGroup);
    }

    public function uploadOnYoutube(Episode $video, $channel, $mqGroup)
    {
        $code        = $video->getCode();
        $programCode = $video->getProgram()->getCode();

        // Шаблоны
        $tpl = new Template($video);

        $now = new \DateTime();
        $published = ($video->getPublish() < $now) && !$video->getTrash();
        $notify    = $video->getPublish() > $now->sub(new \DateInterval('P10D'));

        $youtubeData = array(
            "input"     => "/FS/archive2/$programCode/share/$code.mov",
            "code"      => $code,
            "titleTpl"  => $tpl->title(),
            "notify"    => $notify,
            "published" => $published,
            "channel"   => $channel,
        );
        $playlist = $tpl->playlist();
        if ($published && $playlist != '') {
            $youtubeData["playlist"] = array("title" => $playlist);
        }

        /**
         * @var \AppBundle\Entity\MessageRepository $mr
         */
        $mr = $this->getEntityManager()->getRepository('AppBundle:Message');
        $mr->publish($video->getCode(), 'YoutubeUpload', $youtubeData, 100, 1, $mqGroup);
    }

    public function updateSite(Episode $video, $mqGroup)
    {
        $code        = $video->getCode();

        $task = $video->getTrash() ? 'del' : 'add';

        $siteData = array(
            "code"  => $code,
            "task"  => $task,
        );

        /**
         * @var \AppBundle\Entity\MessageRepository $mr
         */
        $mr = $this->getEntityManager()->getRepository('AppBundle:Message');
        $mr->publish($video->getCode(), 'SyncHopeUa', $siteData, 100, 1, $mqGroup);
    }

    public function updateOnYoutube(Episode $video, $channel, $mqGroup)
    {
        $code = $video->getCode();
        $tpl  = new Template($video);

        $now = new \DateTime();
        $published = ($video->getPublish() < $now) && !$video->getTrash();

        $updateData = array(
            "code"      => $code,
            "titleTpl"  => $tpl->title(),
            "published" => $published,
            'playlist'  => array(
                'title' => $tpl->playlist(),
            ),
            "channel"   => $channel,
        );

        /**
         * @var \AppBundle\Entity\MessageRepository $mr
         */
        $mr = $this->getEntityManager()->getRepository('AppBundle:Message');
        $mr->publish($video->getCode(), 'YoutubeUpdateVideo', $updateData, 100, 1, $mqGroup);
    }
    
    public function getPreset(Episode $episode)
    {
        $selector = new ChannelSelector($episode->getCode());
        switch ($episode->getFormat()) {
            case '1080p50':
                $preset = 'YoutubeFullHD50';
                break;
            case '1080p25':
                $preset = 'YoutubeFullHD25';
                break;
            case '720p50':
                $preset = 'YoutubeHD';
                break;
            default:
                $preset = 'YoutubeSD';
        }

        if ($selector->owner() == 'hoperu') {
            $preset = 'RU' . $preset;
        }
        if ($selector->logo() != 'default') {
            $preset .= '-' . $selector->logo();
        }

        return $preset;
    }
}
