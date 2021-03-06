<?php
namespace AppBundle\Service\Youtube;

use GuzzleHttp;

/**
 * Provides the channel name of selected program
 */
class ChannelSelector
{
    private $code;
    private $channel;
    private $owner;
    private $logoStyle = 'default';
    private $compressor = 1;

    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get channel name by program code
     *
     * @return string
     */
    public function program()
    {
        if ($this->channel == '') {
            $channel = $this->apiRequest($this->code);
            $this->channel = $channel['alias'];
            $this->owner   = $channel['owner'];
            $this->logoStyle = $channel['logoStyle'];
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
        return $this->compressor;
    }

    public function owner()
    {
        $this->program();

        return $this->owner;
    }

    public function logo()
    {
        $this->program();

        return $this->logoStyle;
    }

    private function apiRequest($code)
    {
        $url = 'http://db2.yourhope.tv/api/channel';

        $client = new GuzzleHttp\Client();
        $response = $client->get($url, [
            'query' => [
                'code' => $code,
            ],
            'exceptions' => false,
        ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        return $response->json();
    }
}
