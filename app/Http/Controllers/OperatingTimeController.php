<?php

namespace App\Http\Controllers;

use App\Helpers\MonthStatistic\MonthStatistic;
use App\Http\Requests\HomeRequest;
use App\Models\Account;
use App\Models\OperatingTime;
use App\Services\MonthStatisticCSVExport;
use Illuminate\Support\Carbon;

class OperatingTimeController extends Controller
{
    public function getTable(HomeRequest $request)
    {
        if (! $request->has('accountIds')) {
            return '';
        }
        $accountIds = $request->input('accountIds');
        $month = $request->input('monthId');

        $filteredData = $this->getFilteredData($accountIds, $month);

        return view('home.table', [
            'monthStatistic' => $filteredData,
        ]);
    }

    public function getCSV(HomeRequest $request)
    {
        if (! $request->has('accountIds')) {
            return '';
        }
        $accountIds = (array)$request->input('accountIds');
        $month = $request->input('monthId');

        $filteredData = $this->getFilteredData($accountIds, $month);
        $monthStatisticCSVExport = new MonthStatisticCSVExport($filteredData);
        return $monthStatisticCSVExport->getCSV();
    }

    private function getFilteredData(array $accountIds, $month)
    {
        $startDate = Carbon::create(2018, $month, 1, 0, 0, 0);
        $finishDate = Carbon::create(2018, $month + 1, 0, 23, 59, 59);

        $accounts = Account::whereIn('id', $accountIds)->get();

        $operatingTimes = OperatingTime::with(['computer'])
            ->whereIn('account_id', $accountIds)
            ->where([
                ['start', '>=', $startDate],
                ['finish', '<=', $finishDate],
            ])
            ->get();

        return new MonthStatistic($startDate, $finishDate, $accounts, $operatingTimes);
    }
}
