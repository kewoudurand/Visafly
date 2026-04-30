<?php

//app/Console/Commands/AffiliateCompleteReferrals.php
namespace App\Console\Commands;

use App\Models\User;
use App\Models\Referral;
use App\Services\AffiliationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AffiliateCompleteReferrals extends Command
{
    /**
     * Le nom et la signature de la commande
     */
    protected $signature = 'affiliate:complete-referrals 
                            {--referred_id= : Compléter pour un utilisateur spécifique}
                            {--status=pending : Statut actuel des parrainages}
                            {--days=0 : Parrainages depuis X jours}';

    /**
     * Description de la commande
     */
    protected $description = 'Valider et compléter les parrainages en attente';

    protected $affiliationService;

    public function __construct(AffiliationService $affiliationService)
    {
        parent::__construct();
        $this->affiliationService = $affiliationService;
    }

    /**
     * Exécuter la commande
     */
    public function handle()
    {
        $this->info('🚀 Démarrage de la validation des parrainages...');

        $query = Referral::where('status', $this->option('status'));

        // ✅ Filtrer par utilisateur si spécifié
        if ($this->option('referred_id')) {
            $query->where('referred_id', $this->option('referred_id'));
            $this->info("📍 Filtrage pour l'utilisateur: {$this->option('referred_id')}");
        }

        // ✅ Filtrer par nombre de jours si spécifié
        if ($this->option('days') > 0) {
            $query->where('created_at', '>=', now()->subDays($this->option('days')));
            $this->info("📅 Filtrage depuis {$this->option('days')} jour(s)");
        }

        $referrals = $query->get();

        if ($referrals->isEmpty()) {
            $this->warn('⚠️  Aucun parrainage à valider');
            return Command::SUCCESS;
        }

        $this->info("📊 {$referrals->count()} parrainage(s) trouvé(s)\n");

        // ✅ Créer une barre de progression
        $bar = $this->output->createProgressBar($referrals->count());
        $bar->start();

        $completed = 0;
        $failed = 0;

        foreach ($referrals as $referral) {
            try {
                DB::transaction(function () use ($referral) {
                    // Marquer comme complétée
                    $referral->complete();
                });

                $completed++;
                $bar->advance();
                
            } catch (\Exception $e) {
                $failed++;
                $bar->advance();
                $this->error("\n❌ Erreur pour referral #{$referral->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();

        $this->newLine(2);
        $this->info("✅ Validation terminée!");
        $this->line("   ✓ Complétées: <fg=green>{$completed}</fg=green>");
        $this->line("   ✗ Erreurs: <fg=red>{$failed}</fg=red>");

        return Command::SUCCESS;
    }
}