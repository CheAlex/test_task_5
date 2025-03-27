<?php

declare(strict_types=1);

namespace App\Service\WalletBalanceProvider;

use App\Exception\WalletBalanceProviderException;
use App\Models\Wallet;

interface WalletBalanceProviderInterface
{
    public function supports(Wallet $wallet): bool;

    /**
     * @throws WalletBalanceProviderException
     */
    public function getBalance(Wallet $wallet): string;
}
