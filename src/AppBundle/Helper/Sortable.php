<?php
namespace AppBundle\Helper;

use Doctrine\ORM\Mapping as ORM;

trait Sortable
{

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort( $sort )
    {
        $this->sort = $sort;
    }
}
