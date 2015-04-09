<?php
namespace AppBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class ListParameters
{
    const DEFAULT_PAGE  = 1;
    const DEFAULT_LIMIT = 10;
    const MAX_LIMIT     = 100;

    /**
     * @var int  Current page
     */
    private $page = self::DEFAULT_PAGE;
    /**
     * @var int  Limit on page
     */
    private $limit = self::DEFAULT_LIMIT;
    /**
     * @var array  Custom data
     */
    private $data = [];

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage( $page )
    {
        $page = (int) $page;
        if ($page < 1) {
            $page = self::DEFAULT_PAGE;
        }

        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit( $limit )
    {
        $limit = (int) $limit;
        if ($limit < 1) {
            $limit = self::DEFAULT_LIMIT;
        }
        if ($limit > self::MAX_LIMIT) {
            $limit = self::MAX_LIMIT;
        }

        $this->limit = $limit;
    }

    /**
     * Set custom param
     *
     * @param $key
     * @param $val
     */
    public function set($key, $val)
    {
        $this->data[$key] = $val;
    }

    /**
     * Get custom param
     *
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Creates ListParameters object from Request data
     *
     * @param Request $request
     *
     * @return ListParameters
     */
    public static function createFromRequest(Request $request)
    {
        $params = new self();
        $params->setPage($request->get('page', self::DEFAULT_PAGE));
        $params->setLimit($request->get('limit'), self::DEFAULT_LIMIT);

        return $params;
    }
}
