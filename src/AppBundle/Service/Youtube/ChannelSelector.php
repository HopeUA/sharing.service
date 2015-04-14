<?php
namespace AppBundle\Service\Youtube;

/**
 * Provides the channel name of selected program
 */
class ChannelSelector
{
    const DEFAULT_CHANNEL = 'hopeua';

    private $config;
    private $code;
    private $channel;
    private $compressor = 1;

    public function __construct($code)
    {
        $this->config = [
            'signs' => [
                'programs' => [
                    '[A-Z]{3}Z'
                ]
            ],
            'morning' => [
                'programs' => [
                    'MHKU',
                    'WGKU',
                    'ZGKU',
                    'STKU',
                    'LLKU',
                    'KUKU'
                ]
            ],
        ];
        $this->code   = $code;
    }

    /**
     * Get channel name by program code
     *
     * @return string
     */
    public function program()
    {
        if ($this->channel == '') {
            $this->channel = self::DEFAULT_CHANNEL;

            foreach ($this->config as $channel => $data) {
                if (isset($data['programs'])) {
                    foreach ($data['programs'] as $exp) {
                        if (preg_match("~$exp~", $this->code)) {
                            $this->channel = $channel;
                            break 2;
                        }
                    }
                }
            }
        }

        return $this->channel;
    }

    /**
     * Get compressor ID by program code
     *
     * @return int
     */
    public function compressor()
    {
        $channel = $this->program();

        switch ($channel) {
            case 'morning': $this->compressor = 2; break;
        }

        return $this->compressor;
    }
}