@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Mon planning</h2>
    <p>Voici vos déplacements.</p>
    @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        $daysInMonth = $now->daysInMonth;
        $user = auth()->user();
        $idLivreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur)->value('id_livreur');
        $deplacements = $idLivreur
            ? \App\Models\Planning::where('id_livreur', $idLivreur)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get()
            : collect();
        $jours = $deplacements->map(function($d) { return Carbon::parse($d->date)->day; })->unique();
    @endphp
    <table class="table table-bordered" style="max-width:400px;">
        <thead>
            <tr>
                <th colspan="7" class="text-center">{{ $now->locale('fr_FR')->isoFormat('MMMM Y') }}</th>
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
                                @if ($jours->contains($day))
                                    <a href="{{ route('livreur.deplacements', ['date' => Carbon::create($year, $month, $day)->format('Y-m-d')]) }}" title="Voir déplacements">
                                        <span style="color:red">•</span>
                                    </a>
                                @endif
                            </td>
                            @php $day++; @endphp
                        @endif
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
    <p>Un point orange indique un jour où vous avez un déplacement.</p>
</div>
@endsection 