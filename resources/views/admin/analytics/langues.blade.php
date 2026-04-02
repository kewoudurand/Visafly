{{-- resources/views/admin/analytics/langues.blade.php - CORRIGÉE --}}
@extends('layouts.dashboard')
@section('title', 'Analytics — Tests de langue')

@push('styles')
<style>
.kpi{background:#fff;border-radius:14px;border:1px solid #eee;padding:20px;
     box-shadow:0 2px 8px rgba(27,58,107,.04);}
.kpi-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.kpi-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.an-card{background:#fff;border-radius:14px;border:1px solid #eee;padding:22px;
         box-shadow:0 2px 8px rgba(27,58,107,.04);margin-bottom:20px;}
.an-card-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:16px;
               display:flex;align-items:center;gap:7px;padding-bottom:12px;
               border-bottom:1.5px solid rgba(27,58,107,.06);}
.bar-row{display:flex;align-items:center;gap:10px;margin-bottom:9px;}
.bar-lbl{font-size:12px;color:#555;min-width:100px;text-align:right;flex-shrink:0;}
.bar-track{flex:1;height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden;}
.bar-fill{height:100%;border-radius:4px;}
.bar-val{font-size:12px;font-weight:700;color:#1B3A6B;min-width:30px;}
.table-vf th{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
             letter-spacing:.6px;border:none;padding:12px 14px;background:#f8f9fb;}
.table-vf td{padding:13px 14px;vertical-align:middle;border-bottom:1px solid #f5f5f5;font-size:13px;}
.score-badge{padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.score-good{background:rgba(28,200,138,.1);color:#0f6e56;}
.score-mid {background:rgba(245,166,35,.1);color:#633806;}
.score-bad {background:rgba(226,75,74,.08);color:#a32d2d;}
.filter-input{border:1.5px solid #e8e8e8;border-radius:8px;padding:8px 12px;
              font-size:12px;outline:none;transition:border-color .2s;background:#fafafa;}
.filter-input:focus{border-color:#F5A623;background:#fff;}
.exam-badge{display:inline-flex;align-items:center;justify-content:center;
            padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;color:#fff;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Analytics — Tests de langue</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Suivi des passages et de la progression</p>
    </div>
    <div style="font-size:12px;color:#888;">
        <i class="bi bi-clock me-1"></i>{{ now()->format('d/m/Y H:i') }}
    </div>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi">
            <div class="kpi-num" style="color:#1B3A6B;">{{ $stats['total_passages'] ?? 0 }}</div>
            <div class="kpi-lbl">Passages totaux</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi">
            <div class="kpi-num" style="color:#1cc88a;">{{ $stats['termines'] ?? 0 }}</div>
            <div class="kpi-lbl">Terminés</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi">
            <div class="kpi-num" style="color:#F5A623;">{{ $stats['score_moyen'] ?? 0 }}%</div>
            <div class="kpi-lbl">Score moyen</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi">
            <div class="kpi-num" style="color:#1B3A6B;">{{ $stats['ce_mois'] ?? 0 }}</div>
            <div class="kpi-lbl">Ce mois</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Évolution mensuelle --}}
        <div class="an-card">
            <div class="an-card-title">
                <i class="bi bi-graph-up" style="color:#F5A623;"></i>Passages par mois
            </div>
            @if(($parMois ?? collect())->isNotEmpty())
            <canvas id="passagesChart" height="100"></canvas>
            @else
            <div style="text-align:center;padding:40px;color:#aaa;">
                <i class="bi bi-bar-chart" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px;"></i>
                Pas assez de données
            </div>
            @endif
        </div>

        {{-- Tableau - qui a passé quoi --}}
        <div class="an-card">
            <div class="an-card-title">
                <i class="bi bi-table" style="color:#F5A623;"></i>
                Derniers passages
            </div>
            {{-- Filtres --}}
            <form method="GET" class="d-flex gap-2 mb-3 flex-wrap">
                <select name="langue" class="filter-input">
                    <option value="">Tous les examens</option>
                    @foreach($langues ?? [] as $l)
                    <option value="{{ $l->code }}" {{ request('langue') == $l->code ? 'selected' : '' }}>
                        {{ $l->nom ?? 'N/A' }}
                    </option>
                    @endforeach
                </select>
                <select name="statut" class="filter-input">
                    <option value="">Tous les statuts</option>
                    <option value="termine"  {{ request('statut')=='termine'  ? 'selected' : '' }}>Terminé</option>
                    <option value="en_cours" {{ request('statut')=='en_cours' ? 'selected' : '' }}>En cours</option>
                </select>
                <select name="score" class="filter-input">
                    <option value="">Tous les scores</option>
                    <option value="high" {{ request('score')=='high' ? 'selected' : '' }}>&gt; 60%</option>
                    <option value="mid"  {{ request('score')=='mid'  ? 'selected' : '' }}>40–60%</option>
                    <option value="low"  {{ request('score')=='low'  ? 'selected' : '' }}>&lt; 40%</option>
                </select>
                <button type="submit" style="padding:8px 16px;background:#1B3A6B;color:#fff;
                        border:none;border-radius:8px;font-size:12px;cursor:pointer;">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.analytics.langues') }}"
                style="padding:8px 12px;border:1px solid #ddd;border-radius:8px;
                        color:#666;text-decoration:none;font-size:12px;">Réinitialiser</a>
            </form>

            <div style="overflow-x:auto;">
            <table class="table table-vf mb-0">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Examen</th>
                        <th>Série / Discipline</th>
                        <th>Score</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passages ?? collect() as $p)
                    @php
                        $score = $p->score ?? null;
                        $scoreClass = $score >= 60 ? 'score-good' : ($score >= 40 ? 'score-mid' : 'score-bad');
                        $langue = $p->langue;
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#1B3A6B;font-size:13px;">
                                {{ $p->user?->first_name ?? '—' }}
                            </div>
                            <div style="font-size:11px;color:#888;">{{ $p->user?->email ?? '' }}</div>
                        </td>
                        <td>
                            @if($langue)
                                <span class="exam-badge" style="background:{{ $langue->couleur ?? '#999' }};">
                                    {{ strtoupper($langue->code ?? 'N/A') }}
                                </span>
                                <div style="font-size:11px;color:#666;margin-top:2px;">
                                    {{ $langue->nom ?? '—' }}
                                </div>
                                @else
                                <span style="color:#aaa;font-size:12px;">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size:12px;font-weight:600;color:#333;">
                                {{ $p->serie?->titre ?? $p->serie?->nom ?? '—' }}
                            </div>
                            <div style="font-size:11px;color:#888;">
                                {{ $p->discipline?->nom ?? '' }}
                            </div>
                        </td>
                        <td>
                            @if($score !== null)
                            <span class="score-badge {{ $scoreClass }}">{{ (int)$score }}%</span>
                            @else<span style="color:#aaa;font-size:12px;">—</span>@endif
                        </td>
                        <td>
                            @php
                                $statut = $p->statut ?? 'en_cours';
                                $sc = $statut === 'termine' ? 'score-good' : 'score-mid';
                                $sl = $statut === 'termine' ? 'Terminé' : 'En cours';
                            @endphp
                            <span class="score-badge {{ $sc }}">{{ $sl }}</span>
                        </td>
                        <td style="font-size:12px;color:#888;">
                            {{ $p->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color:#aaa;">
                            Aucun passage trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            @if(($passages ?? collect())->hasPages())
            <div class="mt-3">{{ $passages->links() }}</div>
            @endif
        </div>

    </div>

    <div class="col-lg-4">

        {{-- Passages par examen --}}
        <div class="an-card">
            <div class="an-card-title">
                <i class="bi bi-pie-chart" style="color:#F5A623;"></i>Par examen
            </div>
            @php $totalP = $stats['total_passages'] ?? 1; @endphp
            @forelse($langues ?? [] as $langue)
            @php $nb = $passagesParLangue[$langue->code] ?? 0; $pct = round(($nb / max($totalP, 1)) * 100); @endphp
            <div class="bar-row">
                <span style="display:inline-flex;align-items:center;justify-content:center;
                            width:32px;height:22px;border-radius:5px;
                            background:{{ $langue->couleur ?? '#999' }};color:#fff;
                            font-size:9px;font-weight:800;flex-shrink:0;">
                    {{ strtoupper($langue->code ?? 'N/A') }}
                </span>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $langue->couleur ?? '#999' }};"></div>
                </div>
                <span class="bar-val">{{ $nb }}</span>
            </div>
            @empty
            <p style="font-size:12px;color:#aaa;">Aucune langue</p>
            @endforelse
        </div>

        {{-- Scores par tranche --}}
        <div class="an-card">
            <div class="an-card-title">
                <i class="bi bi-bar-chart" style="color:#F5A623;"></i>Répartition scores
            </div>
            @php
                $tranches = [
                    ['80-100%', $stats['scores']['80_100'] ?? 0, '#1B3A6B'],
                    ['60-79%',  $stats['scores']['60_79']  ?? 0, '#1cc88a'],
                    ['40-59%',  $stats['scores']['40_59']  ?? 0, '#F5A623'],
                    ['0-39%',   $stats['scores']['0_39']   ?? 0, '#E24B4A'],
                ];
                $maxVal = max(array_column($tranches, 1), 1);
            @endphp
            @foreach($tranches as [$lbl, $nb, $color])
            <div class="bar-row">
                <span class="bar-lbl">{{ $lbl }}</span>
                <div class="bar-track">
                    {{-- <div class="bar-fill" style="width:{{ round(($nb/$maxVal)*100) }}%;background:{{ $color }};"></div> --}}
                </div>
                <span class="bar-val">{{ $nb }}</span>
            </div>
            @endforeach
        </div>

        {{-- Top étudiants --}}
        <div class="an-card">
            <div class="an-card-title">
                <i class="bi bi-trophy" style="color:#F5A623;"></i>Top étudiants
            </div>
            @forelse($topEtudiants ?? collect() as $i => $etu)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:26px;height:26px;border-radius:50%;flex-shrink:0;
                            background:{{ $i === 0 ? '#F5A623' : ($i === 1 ? '#aaa' : '#1B3A6B') }};
                            display:flex;align-items:center;justify-content:center;
                            font-size:11px;font-weight:800;color:#fff;">
                    {{ $i + 1 }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:600;color:#1B3A6B;">
                        {{ $etu->user?->first_name ?? '—' }}
                    </div>
                    <div style="font-size:11px;color:#888;">{{ $etu->nb_passages ?? 0 }} passage(s)</div>
                </div>
                <div style="font-size:13px;font-weight:700;color:#1cc88a;">
                    {{ $etu->score_moyen ?? 0 }}%
                </div>
            </div>
            @empty
            <p style="font-size:12px;color:#aaa;text-align:center;padding:12px 0;">Aucun étudiant</p>
            @endforelse
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
@if(($parMois ?? collect())->isNotEmpty())
new Chart(document.getElementById('passagesChart'), {
    type: 'bar',
    data: {
        labels: @json(($parMois ?? collect())->pluck('mois')),
        datasets: [{
            label: 'Passages',
            data:  @json(($parMois ?? collect())->pluck('total')),
            backgroundColor: 'rgba(27,58,107,.7)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { font: { size: 12 }, color: '#888' }, grid: { display: false } },
            y: { ticks: { font: { size: 12 }, color: '#888' }, grid: { color: '#f5f5f5' }, beginAtZero: true },
        }
    }
});
@endif
</script>
@endpush

@endsection