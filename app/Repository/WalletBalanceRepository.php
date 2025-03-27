<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\WalletBalance;

class WalletBalanceRepository
{
    public function add(WalletBalance $walletBalance): void
    {
        $walletBalance->save();
    }
}
