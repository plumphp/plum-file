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
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * ModificationTimeFilter
 *
 * @package   Plum\PlumFile
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
     * @var string|null
     */
    private $property;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @param DateTime[]  $time
     * @param string|null $property
     */
    public function __construct(array $time, $property = null)
    {
        $this->time = $time;
        if ($property) {
            $this->property = $property;
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function filter($item)
    {
        $filename = $this->property ? $this->accessor->getValue($item, $this->property) : $item;
        if ($filename instanceof SplFileInfo) {
            $filename = $filename->getPathname();
        }

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
