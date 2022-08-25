<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Stopwatch;

/**
 * Represents an Event managed by Stopwatch.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class StopwatchEvent
{
    /**
     * @var StopwatchPeriod[]
     */
    private $periods = [];

    /**
     * @var float
     */
    private $origin;

    /**
     * @var string
     */
    private $category;

    /**
     * @var bool
     */
    private $morePrecision;

    /**
     * @var float[]
     */
    private $started = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @param float       $origin        The origin time in milliseconds
     * @param string|null $category      The event category or null to use the default
     * @param bool        $morePrecision If true, time is stored as float to keep the original microsecond precision
     * @param string|null $name          The event name or null to define the name as default
     *
     * @throws \InvalidArgumentException When the raw time is not valid
     */
    public function __construct(float $origin, string $category = null, bool $morePrecision = false, string $name = null)
    {
        $this->origin = $this->formatTime($origin);
        $this->category = \is_string($category) ? $category : 'default';
        $this->morePrecision = $morePrecision;
        $this->name = $name ?? 'default';
    }

    /**
     * Gets the category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Gets the origin in milliseconds.
     *
     * @return float
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Starts a new event period.
     *
     * @return $this
     */
    public function start()
    {
        $this->started[] = $this->getNow();

        return $this;
    }

    /**
     * Stops the last started event period.
     *
     * @return $this
     *
     * @throws \LogicException When stop() is called without a matching call to start()
     */
    public function stop()
    {
        if (!\count($this->started)) {
            throw new \LogicException('stop() called but start() has not been called before.');
        }

        $this->periods[] = new StopwatchPeriod(array_pop($this->started), $this->getNow(), $this->morePrecision);

        return $this;
    }

    /**
     * Checks if the event was started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return !empty($this->started);
    }

    /**
     * Stops the current period and then starts a new one.
     *
     * @return $this
     */
    public function lap()
    {
        return $this->stop()->start();
    }

    /**
     * Stops all non already stopped periods.
     */
    public function ensureStopped()
    {
        while (\count($this->started)) {
            $this->stop();
        }
    }

    /**
     * Gets all event periods.
     *
     * @return StopwatchPeriod[]
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * Gets the relative time of the start of the first period in milliseconds.
     *
     * @return int|float
     */
    public function getStartTime()
    {
        if (isset($this->periods[0])) {
            return $this->periods[0]->getStartTime();
        }

        if ($this->started) {
            return $this->started[0];
        }

        return 0;
    }

    /**
     * Gets the relative time of the end of the last period in milliseconds.
     *
     * @return int|float
     */
    public function getEndTime()
    {
        $count = \count($this->periods);

        return $count ? $this->periods[$count - 1]->getEndTime() : 0;
    }

    /**
     * Gets the duration of the events in milliseconds (including all periods).
     *
     * @return int|float
     */
    public function getDuration()
    {
        $periods = $this->periods;
        $left = \count($this->started);

        for ($i = $left - 1; $i >= 0; --$i) {
            $periods[] = new StopwatchPeriod($this->started[$i], $this->getNow(), $this->morePrecision);
        }

        $total = 0;
        foreach ($periods as $period) {
            $total += $period->getDuration();
        }

        return $total;
    }

    /**
     * Gets the max memory usage of all periods in bytes.
     *
     * @return int
     */
    public function getMemory()
    {
        $memory = 0;
        foreach ($this->periods as $period) {
            if ($period->getMemory() > $memory) {
                $memory = $period->getMemory();
            }
        }

        return $memory;
    }

    /**
     * Return the current time relative to origin in milliseconds.
     *
     * @return float
     */
    protected function getNow()
    {
        return $this->formatTime(microtime(true) * 1000 - $this->origin);
    }

    /**
     * Formats a time.
     *
     * @throws \InvalidArgumentException When the raw time is not valid
     */
    private function formatTime(float $time): float
    {
        return round($time, 1);
    }

    /**
     * Gets the event name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return sprintf('%s/%s: %.2F MiB - %d ms', $this->getCategory(), $this->getName(), $this->getMemory() / 1024 / 1024, $this->getDuration());
    }
}
