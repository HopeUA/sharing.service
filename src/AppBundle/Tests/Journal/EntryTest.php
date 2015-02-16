<?php
namespace AppBundle\Tests\Journal;

use AppBundle\Tests\Fixtures\SampleEntry;

class EntryTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $entry = new SampleEntry();
        $this->assertEquals('sample.entry', $entry->getType());
    }
}
