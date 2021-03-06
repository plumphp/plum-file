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

use DateTime;
use Plum\Plum\Filter\FilterInterface;
use SplFileInfo;

/**
 * ModificationTimeFilter.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2015 Florian Eckerstorfer
 */
class ModificationTimeFilter implements FilterInterface
{
    /**
     * @var DateTime[]
     */
    private $time;

    /**
     * @param DateTime[] $time
     */
    public function __construct(array $time)
    {
        $this->time = $time;
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        $filename = ($item instanceof SplFileInfo) ? $item->getPathname() : $item;

        $modifiedTime = filemtime($filename);
        if (!empty($this->time['after']) && $modifiedTime <= $this->time['after']->getTimestamp()) {
            return false;
        }
        if (!empty($this->time['before']) && $modifiedTime >= $this->time['before']->getTimestamp()) {
            return false;
        }

        return true;
    }
}
