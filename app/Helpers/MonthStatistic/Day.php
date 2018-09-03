<?php

namespace App\Helpers\MonthStatistic;

/**
 * Class Day
 * @package App\Helpers\MonthStatistic
 */
final class Day
{
    /**
     * @var integer
     */
    private $index;
    /**
     * @var bool
     */
    private $isWeekend;

    /**
     * Day constructor.
     * @param int $index
     * @param bool $isWeekend
     */
    public function __construct(int $index, bool $isWeekend)
    {
        $this->index = $index;
        $this->isWeekend = $isWeekend;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function isWeekend(): bool
    {
        return $this->isWeekend;
    }
}