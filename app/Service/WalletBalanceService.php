<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Wallet;
use App\Models\WalletBalance;
use App\Repository\WalletBalanceRepository;
use App\Service\WalletBalanceProvider\WalletBalanceProviderInterface;
use Illuminate\Support\Carbon;

readonly class WalletBalanceService
{
    public function __construct(
        private WalletBalanceRepository $walletBalanceRepository,
        private WalletBalanceProviderInterface $walletBalanceProvider,
    ) {
    }

    public function updateWalletBalance(Wallet $wallet): void
    {
        $walletBalance = new WalletBalance();
        $walletBalance->balance = $this->walletBalanceProvider->getBalance($wallet);
        $walletBalance->created_at = new Carbon();
        $walletBalance->wallet_id = $wallet->id;

        $this->walletBalanceRepository->add($walletBalance);
    }
}
