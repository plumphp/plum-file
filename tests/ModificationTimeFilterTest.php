<?php


namespace Plum\PlumFile;

use DateTime;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use SplFileInfo;

/**
 * ModificationTimeFilterTest
 *
 * @package   Plum\PlumFile
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 * @group     unit
 */
class ModificationTimeFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('fixtures');
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnTrueIfFileIsInRange()
    {
        $filter = new ModificationTimeFilter([
            'after' => new DateTime('-3 days'),
            'before' => new DateTime('-1 day')
        ]);

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-2 days'))->url();
        $this->assertTrue($filter->filter($file));
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnTrueIfFileIsNewerThanAfter()
    {
        $filter = new ModificationTimeFilter(['after' => new DateTime('-3 days')]);

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-2 days'))->url();
        $this->assertTrue($filter->filter($file));
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnTrueIfFileIsOlderThanBefore()
    {
        $filter = new ModificationTimeFilter(['before' => new DateTime('-1 day')]);

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-2 days'))->url();
        $this->assertTrue($filter->filter($file));
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnFalseIfFileIsOlderThanAfter()
    {
        $filter = new ModificationTimeFilter(['after' => new DateTime('-3 days')]);

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-4 days'))->url();
        $this->assertFalse($filter->filter($file));
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnFalseIfFileIsNewThanBefore()
    {
        $filter = new ModificationTimeFilter(['before' => new DateTime('-2 days')]);

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-1 days'))->url();
        $this->assertFalse($filter->filter($file));
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnTrueIsFileIsSplFileInfo()
    {
        $filter = new ModificationTimeFilter(['after' => new DateTime('-3 days')]);

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-2 days'))->url();
        $this->assertTrue($filter->filter(new SplFileInfo($file)));
    }

    /**
     * @test
     * @covers Plum\PlumFile\ModificationTimeFilter::__construct()
     * @covers Plum\PlumFile\ModificationTimeFilter::filter()
     */
    public function filterShouldReturnTrueIsFileNameIsInArray()
    {
        $filter = new ModificationTimeFilter(['after' => new DateTime('-3 days')], '[filename]');

        $file = $this->getMockModifiedFile('foo.txt', new DateTime('-2 days'))->url();
        $this->assertTrue($filter->filter(['filename' => $file]));
    }

    /**
     * @param string   $filename
     * @param DateTime $modifiedTime
     *
     * @return vfsStreamFile
     */
    private function getMockModifiedFile($filename, $modifiedTime)
    {
        $file = new vfsStreamFile('foo.txt');
        $file->lastModified($modifiedTime->getTimestamp());
        $this->root->addChild($file);

        return $file;
    }
}
