<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wallet;

use App\Exception\Wallet\WalletAlreadyExists;
use App\Http\Requests\CreateWalletRequest;
use App\Service\WalletService;
use Illuminate\Http\Exceptions\HttpResponseException;

readonly class CreateWalletAction
{
    public function __construct(
        private WalletService $walletService,
    ) {
    }

    public function __invoke(CreateWalletRequest $request): array
    {
        try {
            $wallet = $this->walletService->createWallet(
                $request->currency,
                $request->address
            );
        } catch (WalletAlreadyExists $exception) {
            throw new HttpResponseException(response()->make($exception->getMessage(), 400));
        }

        return [
            'id' => $wallet->id,
        ];
    }
}
