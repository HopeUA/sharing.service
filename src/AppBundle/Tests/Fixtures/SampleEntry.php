<?php
namespace AppBundle\Tests\Fixtures;

use AppBundle\Journal\Entry;

class SampleEntry extends Entry
{
    protected function init()
    {
        $this->uid = 'Sample';
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
