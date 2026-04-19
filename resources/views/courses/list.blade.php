{{-- resources/views/courses/list.blade.php --}}
@extends('layouts.app')
@section('title', 'Cours d\'Allemand — VisaFly')

@push('styles')
<style>
:root { --marine:#1B3A6B; --or:#F5A623; }
.cl-hero {
    background: linear-gradient(160deg,#0f2347 0%,#1B3A6B 55%,#1e4a8a 100%);
    padding: 64px 24px 190px; position:relative; overflow:hidden; text-align:center;
}
.cl-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background: radial-gradient(ellipse 80% 60% at 50% 120%,rgba(245,166,35,.13) 0%,transparent 70%),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M0 0h40v40H0zM40 40h40v40H40z'/%3E%3C/g%3E%3C/svg%3E");
}
.cl-eyebrow { font-size:.72rem;font-weight:700;letter-spacing:.3em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-bottom:8px; }
.cl-title { font-size:2.8rem;font-weight:900;color:#fff;margin-bottom:10px;line-height:1.1; }
.cl-sub { font-size:1rem;color:rgba(255,255,255,.65);max-width:560px;margin:0 auto 36px; }
.cl-stats { display:flex;justify-content:center;flex-wrap:wrap;gap:12px;position:relative;z-index:1; }
.cl-stat-box { background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:14px 22px;min-width:130px;backdrop-filter:blur(4px); }
.cl-stat-val { font-size:1.5rem;font-weight:900;color:#fff;line-height:1; }
.cl-stat-lbl { font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-top:4px; }
.cl-filters { display:flex;justify-content:center;flex-wrap:wrap;gap:8px;margin-top:32px;position:relative;z-index:1; }
.cl-filter-btn { font-size:.78rem;font-weight:700;padding:.38rem 1.1rem;border-radius:20px;text-decoration:none;transition:all .15s;background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.25); }
.cl-filter-btn:hover,.cl-filter-btn.active { background:var(--or);color:#000;border-color:var(--or); }
.cl-grid { max-width:1140px;margin:-130px auto 80px;padding:0 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:24px;position:relative;z-index:10; }
.cours-card { background:#fff;border-radius:20px;box-shadow:0 8px 30px rgba(0,0,0,.1);overflow:hidden;display:flex;flex-direction:column;transition:transform .25s,box-shadow .25s; }
.cours-card:hover { transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,.16); }
.card-head { padding:26px 22px 22px;position:relative;overflow:hidden;min-height:115px;display:flex;flex-direction:column;justify-content:flex-end; }
.card-head::before { content:'';position:absolute;right:-20px;top:-20px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,.08); }
.card-head::after  { content:'';position:absolute;right:28px;bottom:-30px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.06); }
.card-niveau { display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.22);color:#fff;border-radius:8px;font-size:.62rem;font-weight:800;letter-spacing:.06em;padding:3px 10px;width:fit-content;margin-bottom:10px; }
.card-titre { font-size:1.1rem;font-weight:900;color:#fff;margin-bottom:4px;line-height:1.3;position:relative;z-index:1; }
.card-sous-titre { font-size:.8rem;color:rgba(255,255,255,.8);margin:0;position:relative;z-index:1; }
.card-body { padding:18px 22px 0;flex:1; }
.card-desc { font-size:.82rem;color:#666;line-height:1.65;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden; }
.card-prog-row { display:flex;justify-content:space-between;align-items:center;margin-bottom:5px; }
.card-prog-label,.card-prog-pct { font-size:.72rem;color:#888;font-weight:600; }
.prog-bar { height:5px;background:#f0f0f0;border-radius:10px;overflow:hidden;margin-bottom:14px; }
.prog-fill { height:100%;border-radius:10px;transition:width .6s; }
.card-footer { display:flex;align-items:center;gap:14px;padding:0 22px 14px; }
.card-stat { display:flex;align-items:center;gap:5px;font-size:.75rem;color:#888; }
.card-btn { margin:0 22px 20px;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:12px;border:none;cursor:pointer;font-size:.88rem;font-weight:700;color:#fff;text-decoration:none;transition:filter .2s,transform .2s; }
.card-btn:hover { filter:brightness(1.1);transform:translateY(-1px);color:#fff; }
.cl-empty { grid-column:1/-1;text-align:center;background:#fff;border-radius:20px;padding:60px 30px; }
@media(max-width:640px) { .cl-title{font-size:2rem;} .cl-grid{grid-template-columns:1fr;margin-top:-80px;} .cl-stat-box{min-width:100px;padding:10px 14px;} }
</style>
@endpush

@section('content')
<div class="cl-hero">
    <div style="position:relative;z-index:1">
        <p class="cl-eyebrow">DE</p>
        <h1 class="cl-title">Cours d'Allemand</h1>
        <p class="cl-sub">De A1 à C1 — Apprenez l'allemand pour vos études, votre carrière ou votre immigration</p>
        @auth
        @php
            $uid=$auth_uid=auth()->id();
            $leconsTerm=\App\Models\LessonProgression::where('user_id',$uid)->where('statut','terminee')->count();
            $pointsTotal=\App\Models\LessonProgression::where('user_id',$uid)->sum('points_gagnes');
            $scoreMoyen=\App\Models\LessonProgression::where('user_id',$uid)->where('total_questions','>',0)->avg('score');
            $coursEntames=\App\Models\CourseProgression::where('user_id',$uid)->where('lecons_terminees','>',0)->count();
        @endphp
        <div class="cl-stats">
            <div class="cl-stat-box"><div class="cl-stat-val">{{ $leconsTerm }}</div><div class="cl-stat-lbl">Leçons terminées</div></div>
            <div class="cl-stat-box"><div class="cl-stat-val">{{ $pointsTotal }}</div><div class="cl-stat-lbl">Points gagnés</div></div>
            <div class="cl-stat-box"><div class="cl-stat-val">{{ $scoreMoyen?round($scoreMoyen):0 }}%</div><div class="cl-stat-lbl">Score moyen</div></div>
            <div class="cl-stat-box"><div class="cl-stat-val">{{ $coursEntames }}</div><div class="cl-stat-lbl">Cours entamés</div></div>
        </div>
        @endauth
        @if($cours->isNotEmpty())
        <div class="cl-filters">
            <a href="{{ request()->url() }}" class="cl-filter-btn {{ !request('niveau')?'active':'' }}">Tous</a>
            @foreach($cours->pluck('niveau')->unique()->sort() as $niv)
            <a href="{{ request()->fullUrlWithQuery(['niveau'=>$niv]) }}" class="cl-filter-btn {{ request('niveau')===$niv?'active':'' }}">{{ $niv }}</a>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="cl-grid">
    @forelse($cours as $c)
    @php
        $couleur=$c->couleur??'#1B3A6B';
        $prog=auth()->check()?\App\Models\CourseProgression::where('user_id',auth()->id())->where('cours_id',$c->id)->first():null;
        $pct=$prog?$prog->pourcentage:0;
        $nbL=$c->lessons_count??$c->lecons()->where('publiee',true)->count();
        $dureeH=$c->duree_estimee_minutes?ceil($c->duree_estimee_minutes/60).'h':null;
    @endphp
    <div class="cours-card">
        <div class="card-head" style="background:{{ $couleur }}">
            <span class="card-niveau">{{ $c->niveau }}</span>
            <h3 class="card-titre">{{ $c->titre }}</h3>
            @if($c->sous_titre)<p class="card-sous-titre">{{ $c->sous_titre }}</p>@endif
        </div>
        <div class="card-body">
            @if($c->description)<p class="card-desc">{{ $c->description }}</p>@endif
            <div class="card-prog-row">
                <span class="card-prog-label">Progression</span>
                <span class="card-prog-pct">{{ $pct }}%</span>
            </div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%;background:{{ $couleur }}"></div></div>
        </div>
        <div class="card-footer">
            <span class="card-stat"><i class="bi bi-collection" style="color:{{ $couleur }}"></i>{{ $nbL }} leçon{{ $nbL>1?'s':'' }}</span>
            @if($dureeH)<span class="card-stat"><i class="bi bi-clock" style="color:{{ $couleur }}"></i>{{ $dureeH }}</span>@endif
            @if($c->gratuit)<span class="card-stat" style="color:#198754;background:#e8f8f0;border-radius:6px;padding:2px 8px;font-size:.72rem"><i class="bi bi-unlock-fill"></i> Gratuit</span>@endif
        </div>
        <a href="{{ route('cours.allemand.show', $c->slug) }}" class="card-btn" style="background:{{ $couleur }}">
            <i class="bi bi-lightning-charge-fill"></i>
            {{ $pct>0?'Continuer':'Commencer' }}
        </a>
    </div>
    @empty
    <div class="cl-empty"><div style="font-size:3rem;margin-bottom:16px">📭</div><h5 style="color:var(--marine);font-weight:800">Aucun cours disponible</h5><p class="text-muted">Revenez bientôt !</p></div>
    @endforelse
</div>
@endsection