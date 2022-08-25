<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Countable;
use Iterator;
use ReturnTypeWillChange;
use Serializable;
use SplPriorityQueue as PhpSplPriorityQueue;
use UnexpectedValueException;

use function current;
use function in_array;
use function is_array;
use function is_int;
use function key;
use function max;
use function next;
use function reset;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * This is an efficient implementation of an integer priority queue in PHP
 *
 * This class acts like a queue with insert() and extract(), removing the
 * elements from the queue and it also acts like an Iterator without removing
 * the elements. This behaviour can be used in mixed scenarios with high
 * performance boost.
 */
class FastPriorityQueue implements Iterator, Countable, Serializable
{
    public const EXTR_DATA     = PhpSplPriorityQueue::EXTR_DATA;
    public const EXTR_PRIORITY = PhpSplPriorityQueue::EXTR_PRIORITY;
    public const EXTR_BOTH     = PhpSplPriorityQueue::EXTR_BOTH;

    /** @var integer */
    protected $extractFlag = self::EXTR_DATA;

    /**
     * Elements of the queue, divided by priorities
     *
     * @var array
     */
    protected $values = [];

    /**
     * Array of priorities
     *
     * @var array
     */
    protected $priorities = [];

    /**
     * Array of priorities used for the iteration
     *
     * @var array
     */
    protected $subPriorities = [];

    /**
     * Max priority
     *
     * @var integer|null
     */
    protected $maxPriority;

    /**
     * Total number of elements in the queue
     *
     * @var integer
     */
    protected $count = 0;

    /**
     * Index of the current element in the queue
     *
     * @var integer
     */
    protected $index = 0;

    /**
     * Sub index of the current element in the same priority level
     *
     * @var integer
     */
    protected $subIndex = 0;

    public function __serialize(): array
    {
        $clone = clone $this;
        $clone->setExtractFlags(self::EXTR_BOTH);

        $data = [];
        foreach ($clone as $item) {
            $data[] = $item;
        }

        return $data;
    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $item) {
            $this->insert($item['data'], $item['priority']);
        }
    }

    /**
     * Insert an element in the queue with a specified priority
     *
     * @param mixed $value
     * @param integer $priority
     * @return void
     */
    public function insert($value, $priority)
    {
        if (! is_int($priority)) {
            throw new Exception\InvalidArgumentException('The priority must be an integer');
        }
        $this->values[$priority][] = $value;
        if (! isset($this->priorities[$priority])) {
            $this->priorities[$priority] = $priority;
            $this->maxPriority           = $this->maxPriority === null ? $priority : max($priority, $this->maxPriority);
        }
        ++$this->count;
    }

    /**
     * Extract an element in the queue according to the priority and the
     * order of insertion
     *
     * @return mixed
     */
    public function extract()
    {
        if (! $this->valid()) {
            return false;
        }
        $value = $this->current();
        $this->nextAndRemove();
        return $value;
    }

    /**
     * Remove an item from the queue
     *
     * This is different than {@link extract()}; its purpose is to dequeue an
     * item.
     *
     * Note: this removes the first item matching the provided item found. If
     * the same item has been added multiple times, it will not remove other
     * instances.
     *
     * @param  mixed $datum
     * @return bool False if the item was not found, true otherwise.
     */
    public function remove($datum)
    {
        $currentIndex    = $this->index;
        $currentSubIndex = $this->subIndex;
        $currentPriority = $this->maxPriority;

        $this->rewind();
        while ($this->valid()) {
            if (current($this->values[$this->maxPriority]) === $datum) {
                $index = key($this->values[$this->maxPriority]);
                unset($this->values[$this->maxPriority][$index]);

                // The `next()` method advances the internal array pointer, so we need to use the `reset()` function,
                // otherwise we would lose all elements before the place the pointer points.
                reset($this->values[$this->maxPriority]);

                $this->index    = $currentIndex;
                $this->subIndex = $currentSubIndex;

                // If the array is empty we need to destroy the unnecessary priority,
                // otherwise we would end up with an incorrect value of `$this->count`
                // {@see \Laminas\Stdlib\FastPriorityQueue::nextAndRemove()}.
                if (empty($this->values[$this->maxPriority])) {
                    unset($this->values[$this->maxPriority]);
                    unset($this->priorities[$this->maxPriority]);
                    if ($this->maxPriority === $currentPriority) {
                        $this->subIndex = 0;
                    }
                }

                $this->maxPriority = empty($this->priorities) ? null : max($this->priorities);
                --$this->count;
                return true;
            }
            $this->next();
        }
        return false;
    }

    /**
     * Get the total number of elements in the queue
     *
     * @return int
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return $this->count;
    }

    /**
     * Get the current element in the queue
     *
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function current()
    {
        switch ($this->extractFlag) {
            case self::EXTR_DATA:
                return current($this->values[$this->maxPriority]);
            case self::EXTR_PRIORITY:
                return $this->maxPriority;
            case self::EXTR_BOTH:
                return [
                    'data'     => current($this->values[$this->maxPriority]),
                    'priority' => $this->maxPriority,
                ];
        }
    }

    /**
     * Get the index of the current element in the queue
     *
     * @return int
     */
    #[ReturnTypeWillChange]
    public function key()
    {
        return $this->index;
    }

    /**
     * Set the iterator pointer to the next element in the queue
     * removing the previous element
     *
     * @return void
     */
    protected function nextAndRemove()
    {
        $key = key($this->values[$this->maxPriority]);

        if (false === next($this->values[$this->maxPriority])) {
            unset($this->priorities[$this->maxPriority]);
            unset($this->values[$this->maxPriority]);
            $this->maxPriority = empty($this->priorities) ? null : max($this->priorities);
            $this->subIndex    = -1;
        } else {
            unset($this->values[$this->maxPriority][$key]);
        }
        ++$this->index;
        ++$this->subIndex;
        --$this->count;
    }

    /**
     * Set the iterator pointer to the next element in the queue
     * without removing the previous element
     */
    #[ReturnTypeWillChange]
    public function next()
    {
        if (false === next($this->values[$this->maxPriority])) {
            unset($this->subPriorities[$this->maxPriority]);
            reset($this->values[$this->maxPriority]);
            $this->maxPriority = empty($this->subPriorities) ? null : max($this->subPriorities);
            $this->subIndex    = -1;
        }
        ++$this->index;
        ++$this->subIndex;
    }

    /**
     * Check if the current iterator is valid
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function valid()
    {
        return isset($this->values[$this->maxPriority]);
    }

    /**
     * Rewind the current iterator
     */
    #[ReturnTypeWillChange]
    public function rewind()
    {
        $this->subPriorities = $this->priorities;
        $this->maxPriority   = empty($this->priorities) ? 0 : max($this->priorities);
        $this->index         = 0;
        $this->subIndex      = 0;
    }

    /**
     * Serialize to an array
     *
     * Array will be priority => data pairs
     *
     * @return array
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
     * Set the extract flag
     *
     * @param integer $flag
     * @return void
     */
    public function setExtractFlags($flag)
    {
        switch ($flag) {
            case self::EXTR_DATA:
            case self::EXTR_PRIORITY:
            case self::EXTR_BOTH:
                $this->extractFlag = $flag;
                break;
            default:
                throw new Exception\InvalidArgumentException("The extract flag specified is not valid");
        }
    }

    /**
     * Check if the queue is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->values);
    }

    /**
     * Does the queue contain the given datum?
     *
     * @param  mixed $datum
     * @return bool
     */
    public function contains($datum)
    {
        foreach ($this->values as $values) {
            if (in_array($datum, $values)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Does the queue have an item with the given priority?
     *
     * @param  int $priority
     * @return bool
     */
    public function hasPriority($priority)
    {
        return isset($this->values[$priority]);
    }
}
