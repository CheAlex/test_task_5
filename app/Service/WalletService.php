<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\Wallet\WalletAlreadyExists;
use App\Models\Wallet;
use App\Repository\WalletRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;

readonly class WalletService
{
    public function __construct(
        private WalletRepository $walletRepository,
        private ConnectionInterface $connectionInterface,
        private WalletBalanceService $walletBalanceService,
    ) {
    }

    /**
     * @throws WalletAlreadyExists
     */
    public function createWallet(string $currency, string $address): Wallet
    {
        $this->connectionInterface->beginTransaction();

        $wallet = new Wallet();
        $wallet->currency = $currency;
        $wallet->address = $address;
        $wallet->created_at = new Carbon();

        try {
            try {
                $this->walletRepository->add($wallet);
            } catch (UniqueConstraintViolationException $uniqueConstraintViolationException) {
                throw WalletAlreadyExists::create($uniqueConstraintViolationException);
            }

            $this->walletBalanceService->updateWalletBalance($wallet);

            $this->connectionInterface->commit();
        } catch (\Throwable $exception) {
            $this->connectionInterface->rollBack();
            throw $exception;
        }

        return $wallet;
    }

    public function find(string $id): ?Wallet
    {
        return $this->walletRepository->find($id);
    }

    /**
     * @return Wallet[]
     */
    public function getList(): array
    {
        return $this->walletRepository->findForList();
    }

    public function updateWalletBalances(): void
    {
        $wallets = $this->walletRepository->findAll();

        foreach ($wallets as $wallet) {
            $this->walletBalanceService->updateWalletBalance($wallet);
        }
    }
}
