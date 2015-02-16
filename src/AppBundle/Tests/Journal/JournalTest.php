<?php
namespace AppBundle\Tests\Journal;

use AppBundle\Tests\Fixtures\SampleEntry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Journal\Journal;

class JournalTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $serializer;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $container = static::$kernel->getContainer();
        $this->em  = $container->get('doctrine')->getManager();
        $this->serializer = $container->get('jms_serializer');

        $query  = 'TRUNCATE TABLE journal';
        $this->em->getConnection()->exec($query);
    }

    public function testWrite()
    {
        $entry = new SampleEntry();
        $entry->setData([
            'key1' => 'va1',
            'key2' => 'val2',
        ]);

        // Write entry
        $journal = new Journal($this->em, $this->serializer);
        $journal->write($entry);

        // Check
        $query  = 'SELECT * FROM journal';
        $result = $this->em->getConnection()->query($query)->fetch();

        $this->assertEquals( 'Sample',       $result['uid'] );
        $this->assertEquals( 'sample.entry', $result['type'] );
        $this->assertEquals( '{"key1":"va1","key2":"val2"}', $result['data'] );
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
}
