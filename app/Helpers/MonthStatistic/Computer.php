<?php

namespace App\Helpers\MonthStatistic;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class Computer
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Collection
     */
    private $days;

    /**
     * Computer constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->days = new Collection();
    }

    /**
     * @param int $dayIndex
     * @param WorkDay $workDay
     */
    public function addWorkDay(int $dayIndex, WorkDay $workDay) : void
    {
        $this->days[$dayIndex] = $workDay;
    }

    /**
     * @param int $dayIndex
     * @return WorkDay
     */
    public function getWorkDay(int $dayIndex) : WorkDay
    {
        if ($this->hasWorkDay($dayIndex)) {
            return $this->days->get($dayIndex);
        }

        throw new InvalidArgumentException("No WorkDay for {$dayIndex}");
    }

    /**
     * @param int $dayIndex
     * @return bool
     */
    public function hasWorkDay(int $dayIndex) : bool
    {
        return $this->days->has($dayIndex);
    }

    /**
     * @return string
     */
    public function getAverageText()
    {
        $count = $this->days->count();
        if ($count === 0) {
            return '';
        }

        $total = $this->days->sum(function (WorkDay $day) {
            return $day->getDuration();
        });
        $average = round($total / $count);

        return Carbon::createFromTimestampUTC($average)->format('H:i');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}