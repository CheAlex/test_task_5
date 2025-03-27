<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Service\WalletService;
use Illuminate\Console\Command;

class UpdateWalletBalances extends Command
{
    protected $signature = 'app:update-wallet-balances';

    protected $description = 'Update wallet balances';

    public function handle(WalletService $walletService)
    {
        // По-хорошему здесь в команде нужно накидывать в очередь командные месседжи "обнови баланс", чтоб один упавший
        // эксплорер не запорол обновление всех последующих. Плюс можно будет распаралелить. Скорее всего, нужно будет
        // заюзать рейт-лимитер, чтоб влезть в ограничение ко-ва обращений к API эксплорерам.

        $walletService->updateWalletBalances();
    }
}
