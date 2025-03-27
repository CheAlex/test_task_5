<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wallet;

use App\Models\Wallet;
use App\Service\WalletService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

readonly class GetWalletAction
{
    public function __construct(
        private WalletService $walletService
    ) {
    }

    public function __invoke(string $id): Wallet
    {
        $wallet = $this->walletService->find($id);

        if (null === $wallet) {
            throw new HttpResponseException(new Response('Wallet not found', 400));
        }

        return $wallet;
    }
}
