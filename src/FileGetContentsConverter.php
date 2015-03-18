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

use Cocur\Vale\Vale;
use Plum\Plum\Converter\ConverterInterface;
use SplFileInfo;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * FileGetContentsConverter
 *
 * @package   Plum\PlumFile
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014 Florian Eckerstorfer
 */
class FileGetContentsConverter implements ConverterInterface
{
    /**
     * @var string|null
     */
    private $filenameProperty;

    /**
     * @var string|null
     */
    private $contentProperty;

    /**
     * @param string|array|null $filenameProperty
     * @param string|array|null $contentProperty
     */
    public function __construct($filenameProperty = null, $contentProperty = null)
    {
        if ($contentProperty && !$filenameProperty) {
            throw new \InvalidArgumentException('Cannot use content property if no filename property is set.');
        }
        $this->filenameProperty = $filenameProperty;
        $this->contentProperty  = $contentProperty;
    }

    /**
     * @param array|object|SplFileInfo|string $item
     *
     * @return array|object|string
     */
    public function convert($item)
    {
        $filename = $this->filenameProperty ? Vale::get($item, $this->filenameProperty) : $item;
        if ($item instanceof SplFileInfo) {
            $filename = $item->getPathname();
        }
        if (!is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf('The given file "%s" is not readable.', $filename));
        }

        $content = file_get_contents($filename);

        return $this->contentProperty ? Vale::set($item, $this->contentProperty, $content) : $content;
    }
}
