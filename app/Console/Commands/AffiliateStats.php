<?php

// FILE: app/Console/Commands/AffiliateStats.php
namespace App\Console\Commands;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Console\Command;

class AffiliateStats extends Command
{
    /**
     * Le nom et la signature de la commande
     */
    protected $signature = 'affiliate:stats 
                            {--user_id= : Stats pour un utilisateur}
                            {--top=10 : Top N affiliés}';

    /**
     * Description de la commande
     */
    protected $description = 'Afficher les statistiques d\'affiliation';

    /**
     * Exécuter la commande
     */
    public function handle()
    {
        if ($this->option('user_id')) {
            $this->showUserStats();
        } else {
            $this->showGlobalStats();
        }
    }

    /**
     * Afficher les stats d'un utilisateur spécifique
     */
    private function showUserStats()
    {
        $user = User::find($this->option('user_id'));

        if (!$user) {
            $this->error('❌ Utilisateur non trouvé');
            return;
        }

        $this->info("\n📊 STATISTIQUES D'AFFILIATION");
        $this->line("═══════════════════════════════════");
        $this->line("Utilisateur: <fg=cyan>{$user->name}</fg=cyan>");
        $this->line("Email: <fg=cyan>{$user->email}</fg=cyan>");
        $this->line("Code: <fg=yellow>{$user->referral_code}</fg=yellow>");

        $totalReferrals = $user->referrals()->count();
        $activeReferrals = $user->referrals()->where('is_active_affiliate', true)->count();

        $this->newLine();
        $this->line("👥 PARRAINAGES");
        $this->line("─────────────────────────────────");
        $this->line("  Total: <fg=green>{$totalReferrals}</fg=green>");
        $this->line("  Actifs: <fg=green>{$activeReferrals}</fg=green>");
        $this->line("  Inactifs: <fg=red>" . ($totalReferrals - $activeReferrals) . "</fg=red>");

        $pending = $user->affiliateActivity()->where('status', 'pending')->sum('commission');
        $completed = $user->affiliateActivity()->where('status', 'completed')->sum('commission');
        $withdrawn = $user->affiliateActivity()->where('status', 'withdrawn')->sum('commission');

        $this->newLine();
        $this->line("💰 COMMISSIONS");
        $this->line("─────────────────────────────────");
        $this->line("  En attente: <fg=yellow>" . number_format($pending, 0) . " F</fg=yellow>");
        $this->line("  Complétées: <fg=green>" . number_format($completed, 0) . " F</fg=green>");
        $this->line("  Retirées: <fg=cyan>" . number_format($withdrawn, 0) . " F</fg=cyan>");
        $this->line("  Totales: <fg=magenta>" . number_format($pending + $completed + $withdrawn, 0) . " F</fg=magenta>");

        $wallet = $user->affiliateWallet;
        $this->newLine();
        $this->line("💳 PORTEFEUILLE");
        $this->line("─────────────────────────────────");
        $this->line("  Solde actuel: <fg=green>" . number_format($wallet->balance, 0) . " F</fg=green>");
        $this->line("  Total gagné: <fg=green>" . number_format($wallet->total_earned, 0) . " F</fg=green>");
        $this->line("  Total retiré: <fg=cyan>" . number_format($wallet->total_withdrawn, 0) . " F</fg=cyan>");

        $this->newLine();
    }

    /**
     * Afficher les stats globales
     */
    private function showGlobalStats()
    {
        $this->info("\n📊 STATISTIQUES GLOBALES D'AFFILIATION");
        $this->line("═══════════════════════════════════════════");

        // Total utilisateurs
        $totalUsers = User::count();
        $affiliatedUsers = User::whereNotNull('referred_by')->count();

        $this->newLine();
        $this->line("👥 UTILISATEURS");
        $this->line("─────────────────────────────────");
        $this->line("  Total: <fg=green>{$totalUsers}</fg=green>");
        $this->line("  Parrainés: <fg=green>{$affiliatedUsers}</fg=green>");
        $this->line("  % parrainés: <fg=yellow>" . round(($affiliatedUsers / max($totalUsers, 1)) * 100, 2) . "%</fg=yellow>");

        // Commissions totales
        $totalCommissions = Referral::sum('commission');
        $pendingCommissions = Referral::where('status', 'pending')->sum('commission');
        $completedCommissions = Referral::where('status', 'completed')->sum('commission');
        $withdrawnCommissions = Referral::where('status', 'withdrawn')->sum('commission');

        $this->newLine();
        $this->line("💰 COMMISSIONS GLOBALES");
        $this->line("─────────────────────────────────");
        $this->line("  En attente: <fg=yellow>" . number_format($pendingCommissions, 0) . " F</fg=yellow>");
        $this->line("  Complétées: <fg=green>" . number_format($completedCommissions, 0) . " F</fg=green>");
        $this->line("  Retirées: <fg=cyan>" . number_format($withdrawnCommissions, 0) . " F</fg=cyan>");
        $this->line("  Totales: <fg=magenta>" . number_format($totalCommissions, 0) . " F</fg=magenta>");

        // Top affiliés
        $topAffiliates = User::withCount('referrals')
                            ->having('referrals_count', '>', 0)
                            ->orderByDesc('referrals_count')
                            ->limit($this->option('top'))
                            ->get();

        $this->newLine();
        $this->line("🏆 TOP " . $this->option('top') . " AFFILIÉS");
        $this->line("─────────────────────────────────");

        foreach ($topAffiliates as $key => $user) {
            $commission = $user->affiliateActivity()->where('status', 'completed')->sum('commission');
            $medal = match($key) {
                0 => '🥇',
                1 => '🥈',
                2 => '🥉',
                default => '  '
            };

            $this->line("{$medal} #" . ($key + 1) . " {$user->name}: <fg=green>{$user->referrals_count} parrainages</fg=green> | <fg=cyan>" . number_format($commission, 0) . " F</fg=cyan>");
        }

        $this->newLine();
    }
}