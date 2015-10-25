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

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SplFileInfo;

/**
 * FileGetContentsConverterTest.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 * @group     unit
 */
class FileGetContentsConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileGetContentsConverter */
    private $converter;

    /** @var vfsStreamDirectory */
    private $root;

    public function setUp()
    {
        $structure  = ['foo.txt' => 'Hello World'];
        $this->root = vfsStream::setup('fixtures', null, $structure);

        $this->converter = new FileGetContentsConverter();
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileGetContentsConverter::convert()
     */
    public function convertShouldGetContentIfSplFileInfoGiven()
    {
        $file = new SplFileInfo(vfsStream::url('fixtures/foo.txt'));
        $item = $this->converter->convert($file);

        $this->assertEquals('Hello World', $item);
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileGetContentsConverter::convert()
     */
    public function convertShouldReturnContentIfFilenameGiven()
    {
        $item = $this->converter->convert(vfsStream::url('fixtures/foo.txt'));

        $this->assertEquals('Hello World', $item);
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileGetContentsConverter::__construct()
     * @covers Plum\PlumFile\FileGetContentsConverter::convert()
     */
    public function convertShouldReturnContentIfArrayGiven()
    {
        $converter = new FileGetContentsConverter(['filename']);
        $item      = $converter->convert(['filename' => vfsStream::url('fixtures/foo.txt')]);

        $this->assertEquals('Hello World', $item);
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileGetContentsConverter::__construct()
     * @covers Plum\PlumFile\FileGetContentsConverter::convert()
     */
    public function convertShouldReturnItemWithContentIfArrayGiven()
    {
        $converter = new FileGetContentsConverter(['filename'], ['content']);
        $item      = $converter->convert(['filename' => vfsStream::url('fixtures/foo.txt')]);

        $this->assertEquals('Hello World', $item['content']);
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileGetContentsConverter::__construct()
     * @covers Plum\PlumFile\FileGetContentsConverter::convert()
     */
    public function convertShouldThrowExceptionIfContentPropertyButNoFilenamePropertyGiven()
    {
        try {
            new FileGetContentsConverter(null, ['content']);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     * @covers Plum\PlumFile\FileGetContentsConverter::convert()
     */
    public function convertShouldThrowExceptionIfFileDoesNotExists()
    {
        try {
            $file = new SplFileInfo(__DIR__.'/invalid');
            $this->converter->convert($file);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $e) {
            $this->assertTrue(true);
            $this->assertStringEndsWith('is not readable.', $e->getMessage());
        }
    }
}
