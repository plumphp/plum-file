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
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @param string|null $filenameProperty
     * @param string|null $contentProperty
     */
    public function __construct($filenameProperty = null, $contentProperty = null)
    {
        if ($contentProperty && !$filenameProperty) {
            throw new \InvalidArgumentException('Cannot use content property if no filename property is set.');
        }
        $this->filenameProperty = $filenameProperty;
        $this->contentProperty  = $contentProperty;
        if ($filenameProperty) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }
    }

    /**
     * @param array|object|SplFileInfo|string $item
     *
     * @return array|object|string
     */
    public function convert($item)
    {
        $filename = $this->filenameProperty ? $this->accessor->getValue($item, $this->filenameProperty) : $item;
        if ($item instanceof SplFileInfo) {
            $filename = $item->getPathname();
        }
        if (!is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf('The given file "%s" is not readable.', $filename));
        }

        $content = file_get_contents($filename);

        if ($this->contentProperty) {
            $this->accessor->setValue($item, $this->contentProperty, $content);
        } else {
            $item = $content;
        }

        return $item;
    }
}
