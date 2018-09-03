<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Str;
use League\Csv\Reader;

/**
 * Class AccountCSVImport
 * @package App\Services
 */
class AccountCSVImport
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
        $csv->setDelimiter("\t");
        $records = $csv->getRecords(['name', 'sname','sid']);

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
        $recordName = $record['name'];
        $recordName = mb_convert_encoding($recordName, 'utf-8','cp-1251');
        $recordSname = Str::lower($record['sname']);
        $recordSname = mb_convert_encoding($recordSname, 'utf-8','cp-1251');

        $account = Account::where('sname', $recordSname)->first();

        if ($account instanceof Account) {
            return;
        }
        $account = new Account();
        $account->name = $recordName;
        $account->sname = $recordSname;

        try {
            $account->saveOrFail();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}