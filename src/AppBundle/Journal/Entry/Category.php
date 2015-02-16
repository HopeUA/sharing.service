<?php
namespace AppBundle\Journal\Entry;

use AppBundle\Journal\Entry;

abstract class Category extends Entry
{
    /**
     * @param \AppBundle\Entity\Category $category
     */
    public function setData($category)
    {
        $this->uid  = $category->getId();
        $this->data = $category;
    }
}