<table class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Пользователь</th>
                @foreach ($monthStatistic->getDays() as $day)
                    <th scope="col" @if ($day->isWeekend()) class="day-free" @endif>{{ $day->getIndex() }}</th>
                @endforeach
            <th scope="col">Среднее</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthStatistic->getAccounts() as $account)

            <tr class="table-primary">
               <th scope="row">
                   {{ $loop->iteration }}
               </th>
                <td colspan="{{ count($monthStatistic->getDays()) + 3 }}">{{ $account->getName() }}</td>
            </tr>

            @foreach($account->getComputers() as $computer)
            <tr>
                <th scope="row"></th>
                <td>{{ $computer->getName() }}</td>

                @foreach($monthStatistic->getDays() as $day)
                    @if ($computer->hasWorkDay($day->getIndex()))
                        @php $workDay = $computer->getWorkDay($day->getIndex()); @endphp
                        <td @if($workDay->isImperfection())
                                class="lower"
                            @elseif($workDay->isPerfection())
                                class="higher"
                            @endif
                            data-toggle="popover"
                            data-placement="top"
                            data-content="{{ $workDay->getIntervalText() }}">
                            {{ $workDay->getDurationText() }}
                        </td>
                    @elseif ($day->isWeekend())
                        <td class="day-free"></td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td>{{ $computer->getAverageText() }}</td>
            </tr>

            @endforeach
        @endforeach
    </tbody>
</table>

<div class="table-legend">
    <p class="table-day-free"><span class="legend-span day-free"></span> - Выходной</p>
    <p class="table-higher"><span class="legend-span higher"></span> - Рабочее время выше 9:00 часов</p>
    <p class="table-lower"><span class="legend-span lower"></span> - Рабочее время ниже 8:24</p>
</div>
