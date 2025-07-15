@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Mon calendrier de disponibilités</h2>
    <p>Voici vos interventions à venir. Les jours avec un point (•) indiquent une intervention.</p>
    @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        $daysInMonth = $now->daysInMonth;
        $interventionDays = $interventions->map(function($i) { return Carbon::parse($i->date)->day; })->unique();
    @endphp
    <table class="table table-bordered" style="max-width:400px;">
        <thead>
            <tr>
                <th colspan="7" class="text-center">{{ $now->format('F Y') }}</th>
            </tr>
            <tr>
                <th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th>
            </tr>
        </thead>
        <tbody>
            @php
                $firstDayOfMonth = Carbon::create($year, $month, 1);
                $startDayOfWeek = $firstDayOfMonth->isoWeekday(); // 1 (Lun) à 7 (Dim)
                $day = 1;
            @endphp
            @for ($row = 0; $day <= $daysInMonth; $row++)
                <tr>
                    @for ($col = 1; $col <= 7; $col++)
                        @if ($row === 0 && $col < $startDayOfWeek)
                            <td></td>
                        @elseif ($day > $daysInMonth)
                            <td></td>
                        @else
                            <td class="text-center">
                                {{ $day }}
                                @if ($interventionDays->contains($day))
                                    <span style="color:red;">•</span>
                                @endif
                            </td>
                            @php $day++; @endphp
                        @endif
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection