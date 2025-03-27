<?php

declare(strict_types=1);

namespace App\Providers;

use App\Service\WalletBalanceProvider\BlockchairWalletBalanceProvider;
use App\Service\WalletBalanceProvider\EtherScanWalletBalanceProvider;
use App\Service\WalletBalanceProvider\WalletBalanceProviderComposite;
use App\Service\WalletBalanceProvider\WalletBalanceProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->tag([EtherScanWalletBalanceProvider::class], 'app.wallet_balance_provider');
        $this->app->tag([BlockchairWalletBalanceProvider::class], 'app.wallet_balance_provider');

        $this->app->when(WalletBalanceProviderComposite::class)
            ->needs('$walletBalanceProviders')
            ->giveTagged('app.wallet_balance_provider');

        $this->app->when(EtherScanWalletBalanceProvider::class)
            ->needs('$apiKey')
            ->give(env('ETHERSCAN_API_KEY'));

        $this->app->when(BlockchairWalletBalanceProvider::class)
            ->needs('$apiKey')
            ->give(env('BLOCKCHAIR_API_KEY'));

        $this->app->bind(RequestFactoryInterface::class, HttpFactory::class);
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(WalletBalanceProviderInterface::class, WalletBalanceProviderComposite::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
