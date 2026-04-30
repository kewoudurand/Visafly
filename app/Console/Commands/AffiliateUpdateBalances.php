<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\AffiliateWallet;
use App\Models\Referral;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AffiliateUpdateBalances extends Command
{
    /**
     * Le nom et la signature de la commande
     */
    protected $signature = 'affiliate:update-balances 
                            {--user_id= : Mettre à jour un utilisateur spécifique}
                            {--status=completed : Statut des referrals à compter}';

    /**
     * Description de la commande
     */
    protected $description = 'Recalculer et mettre à jour les soldes des affiliés';

    /**
     * Exécuter la commande
     */
    public function handle()
    {
        $this->info('💰 Mise à jour des soldes des affiliés...');

        $query = User::whereHas('affiliateWallet');

        // ✅ Filtrer par utilisateur si spécifié
        if ($this->option('user_id')) {
            $query->where('id', $this->option('user_id'));
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->warn('⚠️  Aucun affilié trouvé');
            return Command::SUCCESS;
        }

        $this->info("📊 Mise à jour de {$users->count()} affilié(s)\n");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $updated = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                DB::transaction(function () use ($user) {
                    $wallet = $user->affiliateWallet;

                    // Recalculer le solde à partir des referrals
                    $completed = $user->affiliateActivity()
                                    ->where('status', 'completed')
                                    ->sum('commission');

                    $withdrawn = $user->affiliateActivity()
                                    ->where('status', 'withdrawn')
                                    ->sum('commission');

                    $pending = $user->affiliateActivity()
                                  ->where('status', 'pending')
                                  ->sum('commission');

                    // Balance = Complétées - Retirées
                    $balance = $completed - $withdrawn;

                    // Mettre à jour
                    $wallet->update([
                        'balance' => $balance,
                        'total_earned' => $completed,
                        'total_withdrawn' => $withdrawn,
                    ]);
                });

                $updated++;
                $bar->advance();

            } catch (\Exception $e) {
                $failed++;
                $bar->advance();
                $this->error("\n❌ Erreur pour user #{$user->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();

        $this->newLine(2);
        $this->info("✅ Soldes mis à jour!");
        $this->line("   ✓ Actualisés: <fg=green>{$updated}</fg=green>");
        $this->line("   ✗ Erreurs: <fg=red>{$failed}</fg=red>");

        return Command::SUCCESS;
    }
}