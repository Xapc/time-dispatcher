<?php

namespace App\Helpers\MonthStatistic;

use App\Models\Account as Model;
use App\Models\OperatingTime;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class MonthStatistic
{

    /**
     * @var Collection
     */
    private $days;

    /**
     * @var Collection
     */
    private $accounts;

    /**
     * MonthStatistic constructor.
     */
    public function __construct(
        Carbon $start,
        Carbon $finish,
        Collection $accounts,
        Collection $operatingTimes
    ) {
        $this->days = new Collection();
        $this->accounts = new Collection();

        $this->buildDays($start, $finish);
        $this->buildAccounts($accounts, $operatingTimes);
    }

    /**
     * @return array
     */
    public function getDays(): array
    {
        return $this->days->toArray();
    }

    /**
     * @return array
     */
    public function getAccounts(): array
    {
        return $this->accounts->sortBy(function (Account $account) {
            return $account->getName();
        })->toArray();
    }

    /**
     * @param Carbon $start
     * @param Carbon $finish
     */
    private function buildDays(Carbon $start, Carbon $finish) : void
    {
        $current = clone $start;
        for ($index = $start->day; $index <= $finish->day; $index++ ) {
            $current->day = $index;
            $day = new Day($index, $current->isWeekend());
            $this->days->push($day);
        }
    }

    /**
     * @param Collection $accounts
     * @param Collection $operatingTimes
     */
    private function buildAccounts(Collection $accounts, Collection $operatingTimes) : void
    {
        $operatingTimes = $operatingTimes
//            ->whereInstanceOf(OperatingTime::class) // ??????????????????????
            ->groupBy('account_id');

        foreach ($accounts as $model) {
            /** @var $model Model */
            if ($operatingTimes->has($model->id)) {
                $computers = $operatingTimes->get($model->id);
            } else {
                $computers = new Collection();
            }

            $account = $this->createAccount($model, $computers);
            $this->accounts->push($account);
        }
    }

    /**
     * @param Model $model
     * @param Collection $operatingTimes
     * @return Account
     */
    private function createAccount(Model $model, Collection $operatingTimes) : Account
    {
        $account = new Account($model->name);
        $operatingTimes = $operatingTimes->groupBy('computer_id');
        foreach ($operatingTimes as $items) {
            $computer = $this->createComputer($items);
            $account->addComputer($computer);
        }
        return $account;
    }

    /**
     * @param Collection $operatingTimes
     * @return Computer
     */
    private function createComputer(Collection $operatingTimes) : Computer
    {
        /** @var OperatingTime $first */
        $first = $operatingTimes->first();
        $computer = new Computer($first->computer->name);
        foreach ($operatingTimes as $operatingTime) {
            /** @var OperatingTime $operatingTime */
            $workDay = $this->createWorkDay($operatingTime);
            $computer->addWorkDay($operatingTime->start->day, $workDay);
        }

        return $computer;
    }

    /**
     * @param OperatingTime $operatingTime
     * @return WorkDay
     */
    private function createWorkDay(OperatingTime $operatingTime) : WorkDay
    {
        return new WorkDay($operatingTime->start, $operatingTime->finish);
    }
}