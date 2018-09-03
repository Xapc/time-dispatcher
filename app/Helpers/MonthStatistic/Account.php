<?php

namespace App\Helpers\MonthStatistic;

use Illuminate\Support\Collection;

/**
 * Class Account
 * @package App\Helpers\MonthStatistic
 */
class Account
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Collection
     */
    private $computers;

    /**
     * Account constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->computers = new Collection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Computer $computer
     */
    public function addComputer(Computer $computer) : void
    {
        $this->computers->push($computer);
    }

    /**
     * @return array
     */
    public function getComputers()
    {
        return $this->computers->sortBy(function (Computer $computer) {
            return $computer->getName();
        })->toArray();
    }
}