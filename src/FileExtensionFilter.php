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

use Plum\Plum\Filter\FilterInterface;
use SplFileInfo;

/**
 * FileExtensionFilter
 *
 * @package   Plum\PlumFile
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2014-2015 Florian Eckerstorfer
 */
class FileExtensionFilter implements FilterInterface
{
    /** @var string[] */
    private $extensions;

    /**
     * @param string|string[] $extension
     */
    public function __construct($extension)
    {
        if (is_string($extension) === true) {
            $extension = [$extension];
        }
        $this->extensions = $extension;
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        $filename = ($item instanceof SplFileInfo ? $item->getPathname() : $item);

        foreach ($this->extensions as $extension) {
            if (preg_match(sprintf('/\.%s/', $extension), $filename) === 1) {
                return true;
            }
        }

        return false;
    }
}
