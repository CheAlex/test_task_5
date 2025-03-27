<?php

declare(strict_types=1);

namespace App\Service\WalletBalanceProvider;

use App\Exception\WalletBalanceProviderException;
use App\Models\Wallet;

readonly class WalletBalanceProviderComposite implements WalletBalanceProviderInterface
{
    /**
     * @param WalletBalanceProviderInterface[] $walletBalanceProviders
     */
    public function __construct(
        private array $walletBalanceProviders,
    ) {
    }

    public function supports(Wallet $wallet): bool
    {
        foreach ($this->walletBalanceProviders as $walletBalanceProvider) {
            if ($walletBalanceProvider->supports($wallet)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws WalletBalanceProviderException
     */
    public function getBalance(Wallet $wallet): string
    {
        foreach ($this->walletBalanceProviders as $walletBalanceProvider) {
            if ($walletBalanceProvider->supports($wallet)) {
                return $walletBalanceProvider->getBalance($wallet);
            }
        }

        throw new WalletBalanceProviderException('WalletBalanceProvider not found');
    }
}
