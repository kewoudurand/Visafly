<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TcfSeeder extends Seeder
{
    public function run(): void
    {
        // ── Séries ──
        $series = [
            ['nom'=>'Série 100', 'code'=>'serie_100', 'type'=>'TCF', 'gratuit'=>true,  'ordre'=>1],
            ['nom'=>'Série 148', 'code'=>'serie_148', 'type'=>'TCF', 'gratuit'=>true,  'ordre'=>2],
            ['nom'=>'Série 149', 'code'=>'serie_149', 'type'=>'TCF', 'gratuit'=>false, 'ordre'=>3],
            ['nom'=>'Méthodologie TCF', 'code'=>'methodo_tcf', 'type'=>'TCF', 'gratuit'=>false, 'ordre'=>4],
            ['nom'=>'Série 150', 'code'=>'serie_150', 'type'=>'TCF', 'gratuit'=>false, 'ordre'=>5],
            ['nom'=>'Série 151', 'code'=>'serie_151', 'type'=>'TCF', 'gratuit'=>false, 'ordre'=>6],
            ['nom'=>'Série 100', 'code'=>'tef_serie_100', 'type'=>'TEF', 'gratuit'=>true,  'ordre'=>1],
            ['nom'=>'Série 101', 'code'=>'tef_serie_101', 'type'=>'TEF', 'gratuit'=>false, 'ordre'=>2],
        ];
        foreach ($series as $s) {
            DB::table('tcf_series')->insertOrIgnore(array_merge($s, [
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Disciplines pour Série 100 TCF ──
        $serie100 = DB::table('tcf_series')->where('code','serie_100')->first();

        $disciplines = [
            [
                'nom'            => 'Compréhension écrite',
                'code'           => 'comprehension_ecrite',
                'icone'          => 'bi-book',
                'duree_minutes'  => 60,
                'nb_questions'   => 39,
                'type_questions' => 'qcm',
            ],
            [
                'nom'            => 'Compréhension orale',
                'code'           => 'comprehension_orale',
                'icone'          => 'bi-volume-up',
                'duree_minutes'  => 40,
                'nb_questions'   => 39,
                'type_questions' => 'qcm',
            ],
            [
                'nom'            => 'Expression écrite',
                'code'           => 'expression_ecrite',
                'icone'          => 'bi-pencil-square',
                'duree_minutes'  => 60,
                'nb_questions'   => 3,
                'type_questions' => 'redaction',
            ],
            [
                'nom'            => 'Expression orale',
                'code'           => 'expression_orale',
                'icone'          => 'bi-mic',
                'duree_minutes'  => 12,
                'nb_questions'   => 3,
                'type_questions' => 'redaction',
            ],
        ];

        foreach ($disciplines as $d) {
            DB::table('tcf_disciplines')->insertOrIgnore(array_merge($d, [
                'serie_id'   => $serie100->id,
                'actif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Exemple de question ──
        $discCE = DB::table('tcf_disciplines')
            ->where('serie_id', $serie100->id)
            ->where('code', 'comprehension_ecrite')
            ->first();

        $questionId = DB::table('tcf_questions')->insertGetId([
            'discipline_id'  => $discCE->id,
            'numero'         => 40,
            'consigne'       => "Heure d'ouverture du service consulaire :\n\nLe service consulaire est ouvert au public les lundis, mercredis et jeudis de 8h30 à 14h et les mardis et vendredis de 14h30 à 18h.",
            'type_support'   => 'texte',
            'fichier_support'=> null,
            'enonce'         => 'Quand le service est-il fermé au public ?',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $reponses = [
            ['lettre'=>'A', 'texte'=>'Le lundi matin.',    'est_correcte'=>false],
            ['lettre'=>'B', 'texte'=>'Le mardi matin.',    'est_correcte'=>true],
            ['lettre'=>'C', 'texte'=>'Le mercredi matin.', 'est_correcte'=>false],
            ['lettre'=>'D', 'texte'=>'Le jeudi matin.',    'est_correcte'=>false],
        ];
        foreach ($reponses as $r) {
            DB::table('tcf_reponses')->insert(array_merge($r, [
                'question_id' => $questionId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]));
        }
    }
}
