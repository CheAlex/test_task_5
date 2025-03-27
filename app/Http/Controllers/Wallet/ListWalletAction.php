<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wallet;

use App\Service\WalletService;

readonly class ListWalletAction
{
    public function __construct(
        private WalletService $walletService,
    ) {
    }

    public function __invoke(): array
    {
        return $this->walletService->getList();
    }
}
