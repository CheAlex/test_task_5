<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Wallet;

class WalletRepository
{
    public function add(Wallet $wallet): void
    {
        $wallet->save();
    }

    public function find(string $id): ?Wallet
    {
        return Wallet::find($id);
    }

    /**
     * @return Wallet[]
     */
    public function findAll(): array
    {
        return Wallet::all()->all();
    }

    /**
     * @return Wallet[]
     */
    public function findForList(): array
    {
        return Wallet::with([
            'latestBalance'
        ])->get()->all();
    }
}
