<?php

namespace App\Helpers\MonthStatistic;

use Illuminate\Support\Carbon;

/**
 * Class WorkDay
 * @package App\Helpers\MonthStatistic
 */
final class WorkDay
{
    private const IMPERFECTION = (8 * 60 + 24) * 60; // 8:24:00 in sec
    private const PERFECTION = (9 * 60 + 0) * 60; // 9:00:00 in sec
    /**
     * @var Carbon
     */
    private $start;

    /**
     * @var Carbon
     */
    private $finish;

    /**
     * @var integer
     */
    private $duration;

    /**
     * WorkDay constructor.
     * @param Carbon $start
     * @param Carbon $finish
     */
    public function __construct(Carbon $start, Carbon $finish)
    {
        $this->start = $start;
        $this->finish = $finish;
        $this->duration = $finish->getTimestamp() - $start->getTimestamp();
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getDurationText(): string
    {
        return Carbon::createFromTimestampUTC($this->duration)->format('H:i');
    }

    /**
     * @return string
     */
    public function getIntervalText() : string
    {
        return $this->start->format('H:i') . ' - ' . $this->finish->format('H:i');
    }

    /**
     * @return bool
     */
    public function isImperfection() : bool
    {
        return ($this->duration <= self::IMPERFECTION);
    }

    /**
     * @return bool
     */
    public function isPerfection() : bool
    {
        return ($this->duration >= self::PERFECTION);
    }
}
