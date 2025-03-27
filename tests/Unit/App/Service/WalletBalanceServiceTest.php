<?php

declare(strict_types=1);

namespace Tests\Unit\App\Service;

use App\Models\Wallet;
use App\Models\WalletBalance;
use App\Repository\WalletBalanceRepository;
use App\Service\WalletBalanceProvider\WalletBalanceProviderInterface;
use App\Service\WalletBalanceService;
use PHPUnit\Framework\TestCase;
use Tests\Util\AssertsDateTimeTrait;

class WalletBalanceServiceTest extends TestCase
{
    use AssertsDateTimeTrait;

    private readonly \DateTimeImmutable $testStartedAt;
    private readonly WalletBalanceRepository $walletBalanceRepository;
    private readonly WalletBalanceProviderInterface $walletBalanceProvider;
    private readonly WalletBalanceService $walletBalanceService;

    protected function setUp(): void
    {
        $this->testStartedAt = new \DateTimeImmutable();

        $this->walletBalanceRepository = $this->createMock(WalletBalanceRepository::class);
        $this->walletBalanceProvider = $this->createMock(WalletBalanceProviderInterface::class);
        $this->walletBalanceService = new WalletBalanceService(
            $this->walletBalanceRepository,
            $this->walletBalanceProvider,
        );
    }

    public function testUpdateWalletBalance(): void
    {
        $wallet = new Wallet();
        $wallet->currency = 'BTC';
        $wallet->address = 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh';
        $wallet->id = '288088a8-f345-4c27-83fb-91348922bbe1';

        $balance = '12.34';

        $this->walletBalanceProvider
            ->expects($this->once())
            ->method('getBalance')
            ->with($wallet)
            ->willReturn($balance)
        ;

        $this->walletBalanceRepository
            ->expects($this->once())
            ->method('add')
            ->with(self::callback(function (WalletBalance $walletBalance) use ($balance, $wallet) {
                self::assertSame($balance, $walletBalance->balance);
                self::assertDateTimeInRangeFromStartToCurrentDateTime($walletBalance->created_at, $this->testStartedAt);
                self::assertSame($wallet->id, $walletBalance->wallet_id);
                return true;
            }))
        ;

        $this->walletBalanceService->updateWalletBalance($wallet);
    }
}
