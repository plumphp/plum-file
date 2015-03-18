<?php

/**
 * This file is part of plumphp/plum-file.
 *
 * (c) Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plum\PlumFile;

/**
 * FileExtensionFilterTest
 *
 * @package   Plum\PlumFile
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 * @group     unit
 */
class FileExtensionFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Plum\PlumFile\FileExtensionFilter::__construct()
     * @covers Plum\PlumFile\FileExtensionFilter::filter()
     */
    public function convertShouldReturnTrueIfFileExtensionMatches()
    {
        $converter = new FileExtensionFilter('md');

        $this->assertTrue($converter->filter('test.md'));
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileExtensionFilter::__construct()
     * @covers Plum\PlumFile\FileExtensionFilter::filter()
     */
    public function convertShouldReturnFalseIfFileExtensionNotMatches()
    {
        $converter = new FileExtensionFilter('md');

        $this->assertFalse($converter->filter('test.txt'));
    }
    /**
     * @test
     * @covers Plum\PlumFile\FileExtensionFilter::__construct()
     * @covers Plum\PlumFile\FileExtensionFilter::filter()
     */
    public function convertShouldReturnTrueIfFileExtensionMatchesInArray()
    {
        $converter = new FileExtensionFilter(['md', 'html']);

        $this->assertTrue($converter->filter('test.md'));
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileExtensionFilter::__construct()
     * @covers Plum\PlumFile\FileExtensionFilter::filter()
     */
    public function convertShouldReturnFalseIfFileExtensionNotMatchesInArray()
    {
        $converter = new FileExtensionFilter(['md', 'html']);

        $this->assertFalse($converter->filter('test.txt'));
    }
}
