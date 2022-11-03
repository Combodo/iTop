<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Serializable;
use UnexpectedValueException;

use function array_key_exists;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function serialize;
use function sprintf;
use function unserialize;

use const PHP_INT_MAX;

/**
 * Serializable version of SplPriorityQueue
 *
 * Also, provides predictable heap order for datums added with the same priority
 * (i.e., they will be emitted in the same order they are enqueued).
 *
 * @psalm-type InternalPriority = array{0: mixed, 1: int}
 * @template TValue
 * @template TPriority of InternalPriority
 * @extends \SplPriorityQueue<TPriority, TValue>
 */
class SplPriorityQueue extends \SplPriorityQueue implements Serializable
{
    /** @var int Seed used to ensure queue order for items of the same priority */
    protected $serial = PHP_INT_MAX;

    /**
     * Insert a value with a given priority
     *
     * Utilizes {@var $serial} to ensure that values of equal priority are
     * emitted in the same order in which they are inserted.
     *
     * @param  TValue          $datum
     * @param  TPriority|mixed $priority
     * @return void
     */
    public function insert($datum, $priority)
    {
        if (! is_array($priority)) {
            $priority = [$priority, $this->serial--];
        }

        /** @psalm-var TPriority $priority */

        parent::insert($datum, $priority);
    }

    /**
     * Serialize to an array
     *
     * Array will be priority => data pairs
     *
     * @return list<TValue>
     */
    public function toArray()
    {
        $array = [];
        foreach (clone $this as $item) {
            $array[] = $item;
        }
        return $array;
    }

    /**
     * Serialize
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->__serialize());
    }

    /**
     * Magic method used for serializing of an instance.
     *
     * @return array
     */
    public function __serialize()
    {
        $clone = clone $this;
        $clone->setExtractFlags(self::EXTR_BOTH);

        $data = [];
        foreach ($clone as $item) {
            $data[] = $item;
        }
        return $data;
    }

    /**
     * Deserialize
     *
     * @param  string $data
     * @return void
     */
    public function unserialize($data)
    {
        $toUnserialize = unserialize($data);
        if (! is_array($toUnserialize)) {
            throw new UnexpectedValueException(sprintf(
                'Cannot deserialize %s instance; corrupt serialization data',
                self::class
            ));
        }

        $this->__unserialize($toUnserialize);
    }

    /**
     * Magic method used to rebuild an instance.
     *
     * @param array $data Data array.
     * @return void
     */
    public function __unserialize($data)
    {
        $this->serial = PHP_INT_MAX;

        foreach ($data as $item) {
            if (! is_array($item)) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot deserialize %s instance: corrupt item; expected array, received %s',
                    self::class,
                    is_object($item) ? get_class($item) : gettype($item)
                ));
            }

            if (! array_key_exists('data', $item)) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot deserialize %s instance: corrupt item; missing "data" element',
                    self::class
                ));
            }

            $priority = 1;
            if (array_key_exists('priority', $item)) {
                $priority = (int) $item['priority'];
            }

            /** @psalm-var TValue $item['data'] */

            $this->insert($item['data'], $priority);
        }
    }
}
