<?php

namespace App\Services;

use App\Helpers\MonthStatistic\Account;
use App\Helpers\MonthStatistic\Computer;
use App\Helpers\MonthStatistic\Day;
use App\Helpers\MonthStatistic\MonthStatistic;
use League\Csv\Writer;

class MonthStatisticCSVExport
{
    private $monthStatistic;

    public function __construct(MonthStatistic $monthStatistic)
    {
        $this->monthStatistic = $monthStatistic;
    }

    public function getCSV()
    {
        $csvStructureHeader = [];
        $csvStructureBody = [];

        $csvStructureHeader[] = '#';
        $csvStructureHeader[] = 'Пользователь';
        foreach ($this->monthStatistic->getDays() as $day) {
            /** @var $day Day*/
            $csvStructureHeader[] = $day->getIndex();
        }
        $csvStructureHeader[] = 'Среднее';

        foreach ($this->monthStatistic->getAccounts() as $accountKey => $account) {
            /** @var Account $account */
            $csvStructureBody['account-' . $accountKey][] = $accountKey;
            $csvStructureBody['account-' . $accountKey][] = $account->getName();

            foreach ($account->getComputers() as $computerKey => $computer) {
                /** @var $computer Computer */
                $csvStructureBody['account-' . $accountKey . '-computer-' . $computerKey][] = '';
                $csvStructureBody['account-' . $accountKey . '-computer-' . $computerKey][] = $computer->getName();

                foreach ($this->monthStatistic->getDays() as $day) {
                    /** @var $day Day */
                    if ($computer->hasWorkDay($day->getIndex())) {
                        $workDay = $computer->getWorkDay($day->getIndex());
                        $csvStructureBody['account-' . $accountKey . '-computer-' . $computerKey][] = $workDay->getDurationText();
                    } elseif ($day->isWeekend()) {
                        $csvStructureBody['account-' . $accountKey . '-computer-' . $computerKey][] = '';
                    } else {
                        $csvStructureBody['account-' . $accountKey . '-computer-' . $computerKey][] = '';
                    }
                }

                $csvStructureBody['account-' . $accountKey . '-computer-' . $computerKey][] = $computer->getAverageText();

            }
        }

        $csv = Writer::createFromString('');
        $csv->setDelimiter(";");
        $csv->insertOne($csvStructureHeader);
        $csv->insertAll($csvStructureBody);
        $content = $csv->getContent();
        $content = mb_convert_encoding($content,'cp1251','utf-8');
        $pathInner = base_path() . '/public/media/time-dispatch.csv';
        file_put_contents($pathInner, $content);
        return response()->download($pathInner)->deleteFileAfterSend();
    }
}