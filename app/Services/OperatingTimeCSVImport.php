<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Computer;
use App\Models\OperatingTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\Csv\Reader;

/**
 * Class OperatingTimeCSVImport
 * @package App\Services
 */
class OperatingTimeCSVImport
{

    /**
     * @param string $path
     * @throws \League\Csv\Exception
     * @throws \Throwable
     */
    public function import(string $path) : void
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');
        $records = $csv->getRecords(['date','computer','account','status']);

        foreach ($records as $record) {
            try {
                $this->importRecord($record);
            } catch (\Throwable $exception) {
                throw $exception;
            }
        }
    }

    /**
     * @param array $record
     * @throws \Throwable
     */
    private function importRecord(array $record) : void
    {
        $accountSname = mb_convert_encoding($record['account'], 'cp-1251', 'utf-8');
        $accountSname = Str::substr($accountSname, 3);
        $accountSname = Str::lower($accountSname);
        $computerName = mb_convert_encoding($record['computer'], 'cp-1251', 'utf-8');
        $status = $record['status'];

        $format = 'Y-m-d H:i';
        if (preg_match("/.+\d{2}:\d{2}:\d{2}$/", $record['date']) === 1) {
            $format = 'Y-m-d H:i:s';
        }
        $date = Carbon::createFromFormat($format, $record['date']);
        $start = clone $date;
        $finish = clone $date;

        //  'idle-ON' and 'idle-OFF' wrong status of scan system
        if ($status === 'ON' || $status === 'idle-ON') {
            $finish->setTime(23, 59, 59);
        } elseif ($status === 'OFF' || $status === 'idle-OFF') {
            $start->setTime(0, 0, 0);
        }else{
            throw new \RuntimeException(
                "Invalid imported status \"{$status}\". " .
                "Account data: sname: {$accountSname}, " .
                "name: {$computerName}, " .
                "status: {$status}, " .
                "date: {$date}"
            );
        }

        $computer = Computer::where('name', $computerName)->first();
        if (! $computer instanceof Computer) {
            $computer = new Computer();
            $computer->name = $computerName;
            try {
                $computer->save();
            } catch (\Throwable $exception) {
                throw $exception;
            }
        }

        $startDay = clone $date;
        $startDay->setTime(0, 0, 0);
        $finishDay = clone $date;
        $finishDay->setTime(23, 59, 59);

        $operatingTimeCollection = OperatingTime::whereHas('account', function ($q) use ($accountSname) {
            $q->where('sname', $accountSname);
        })->whereHas('computer', function ($q) use ($computerName) {
            $q->where('name', $computerName);
        })->where([
            ['start', '>=', $startDay],
            ['finish', '<=', $finishDay],
        ])->get();

        /** @var $operatingTimeCollection Collection */
        if ($operatingTimeCollection->isEmpty()) {
            try {
                $this->createNewOperatingTime($accountSname, $computerName, $start, $finish);
            } catch (\Throwable $exception) {
                throw $exception;
            }

        } elseif ($operatingTimeCollection->count() === 1) {

            /** @var OperatingTime $operatingTime */
            $operatingTime = $operatingTimeCollection->first();

            try {
                $this->updateExistingOperatingTime($operatingTime, $start, $finish, $status);
            } catch (\Throwable $exception) {
                throw $exception;
            }

        } else {
            throw new \RuntimeException(
                "Для входных данных обнаружено несколько временных диапазонов: " .
                "{$accountSname}, {$computerName}, {$start}, {$finish}"
            );
        }
    }

    /**
     * @param string $accountSname
     * @param string $computerName
     * @param Carbon $start
     * @param Carbon $finish
     * @throws \Throwable
     */
    private function createNewOperatingTime(
        string $accountSname,
        string $computerName,
        Carbon $start,
        Carbon $finish
    ) : void {
        try {
            /** @var Account $account */
            $account = Account::where('sname', $accountSname)->firstOrFail();
        } catch (\Throwable $exception) {
            throw $exception;
        }

        try {
            /** @var Computer $computer */
            $computer = Computer::where('name', $computerName)->firstOrFail();
        } catch (\Throwable $exception) {
            throw $exception;
        }

        $operatingTime = new OperatingTime();
        $operatingTime->account_id = $account->id;
        $operatingTime->computer_id = $computer->id;
        $operatingTime->start = $start;
        $operatingTime->finish = $finish;

        try {
            $operatingTime->saveOrFail();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param OperatingTime $operatingTime
     * @param Carbon $start
     * @param Carbon $finish
     * @param string $status
     * @throws \Throwable
     */
    private function updateExistingOperatingTime(
        OperatingTime $operatingTime,
        Carbon $start,
        Carbon $finish,
        string $status
    ) : void {
        if ($status === 'ON' || $status === 'idle-ON') {
            $operatingTime->start = $start;
        } elseif ($status === 'OFF' || $status === 'idle-OFF') {
            $operatingTime->finish = $finish;
        }else{
            throw new \RuntimeException(
                "Invalid imported status \"{$status}\". " .
                "Account data: sname: {$operatingTime->account->sname}, " .
                "name: {$operatingTime->computer->name}, " .
                "OperatingTime id: {$operatingTime->id}"
            );
        }

        try {
            $operatingTime->saveOrFail();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
