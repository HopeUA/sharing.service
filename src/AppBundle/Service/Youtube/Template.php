<?php
namespace AppBundle\Service\Youtube;

use AppBundle\Entity\Episode;

class Template
{
    private $video;

    protected $customEaster = array(
                  'CLMU00414', 'CLMU00514', 'CLMU00614', 'CLMU00714', 'CLMU00814', 'CLMU00914', 'CLMU01014', 'CLMU0114',
                  'CLMU01214', 'CLMU01314', 'CLMU01414', 'CLMU01514', 'CLMU01614', 'CLMU01714', 'CLMU01814');

    protected $sings = array(
                    'from' => array(
                        ' (сурдоперевод)',
                        ' (сурдопереклад)',
                    ),
                    'to' => array(
                        '','',
                    ),
                );


    public function __construct(Episode $video)
    {
        $this->video = $video;
    }

    public function title()
    {
        $video   = $this->video;
        $program = $video->getProgram();
        $n       = $this->getVideoNumbers();

        switch ($program->getCode()) {
            case 'PSCU':
                $title = $program->getTitle() . ' № ' . $n->num . ' (' . $n->year . ')';
                break;
            case 'MVUU':
            case 'PRUU':
            case 'TRUU':
            case 'ADUU':
                $title = '{title}';
                break;
            case 'SRKU': // Метафоры ученичества | Познаем истину [06.01.14]
            case 'SSKU':
                $title = '{title} | ' . $program->getTitle() . ' [' . $video->getPublish()->format('d.m.y') . ']';
                break;
            case 'VAFX':
                $title = '{title} | Удивительные факты [Анонс]';
                break;
            case 'MHKU':
                $title = '{title} | Ранок Надії';
                break;
            default: // На щастя, на долю... | Ваша думка [19/13]
                $title = '{title} | ' . $program->getTitle() . ' [' . $n->num . '/' . $n->year . ']';
        }

        // Удаление сурдоперевода
        $title = str_replace($this->sings['from'], $this->sings['to'], $title);

        /* TODO перенести в titles.yaml */
        // Custom titles
        if (in_array($video->getCode(), $this->customEaster)) {
            $title = '{title} | Христос – наша Пасха [2014]';
        }

        return $title;
    }

    public function playlist()
    {
        $video   = $this->video;
        $program = $video->getProgram();

        switch ($program->getCode()) {
            case 'MHKU':
                $playlist = '';
                break;
            default:
                $playlist = $program->getTitle();
        }

        // Удаление сурдоперевода
        //$playlist = str_replace($this->sings['from'], $this->sings['to'], $playlist);

        /* TODO перенести в titles.yaml */
        // Custom playlist
        if (in_array($video->getCode(), $this->customEaster)) {
            $playlist = 'Концерт "Христос – наша Пасха" [2014]';
        }

        return $playlist;
    }

    public function playlistPosition()
    {
        return 0;
    }

    private function getVideoNumbers()
    {
        $n = array();

        preg_match('~' . $this->video->getProgram()->getCode() . '(\d{3})(\d{2})~', $this->video->getCode(), $m);
        $n['num']  = sprintf('%02d', $m[1]);
        $n['year'] = sprintf('%02d', $m[2]);

        $n = json_decode(json_encode($n));
        return $n;
    }
}