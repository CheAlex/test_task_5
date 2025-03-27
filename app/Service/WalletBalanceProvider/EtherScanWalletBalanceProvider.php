<?php

declare(strict_types=1);

namespace App\Service\WalletBalanceProvider;

use App\Enum\Cryptocurrency;
use App\Exception\WalletBalanceProviderException;
use App\Models\Wallet;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class EtherScanWalletBalanceProvider implements WalletBalanceProviderInterface
{
    private const string API_URL = 'https://api.etherscan.io/v2/api';
    private const string STATUS_SUCCESS = '1';

    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private string $apiKey,
    ) {
    }

    public function supports(Wallet $wallet): bool
    {
        return Cryptocurrency::ETH === $wallet->currency;
    }

    public function getBalance(Wallet $wallet): string
    {
        $url = sprintf(
            '%s?chainid=1&module=account&action=balance&address=%s&tag=latest&apikey=%s',
            self::API_URL,
            $wallet->address,
            $this->apiKey
        );

        $response = $this->sendRequest($url);

        if (self::STATUS_SUCCESS !== $response['status']) {
            throw new WalletBalanceProviderException(sprintf(
                'EtherScan API error: "%s"',
                $response['message']),
                (int) $response['status']
            );
        }

        // для денег удобнее использовать https://github.com/moneyphp/money - там задать инфо
        // "сколько знаков после запятой для такой-то валюты" и здесь это юзать
        return rtrim(
            rtrim(
                bcdiv($response['result'], '1000000000000000000', 18),
                '0'
            ),
            '.'
        );
    }

    /**
     * @throws WalletBalanceProviderException
     */
    private function sendRequest(string $url): array
    {
        $request = $this->requestFactory->createRequest('GET', $url);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new WalletBalanceProviderException($e->getMessage(), $e->getCode(), $e);
        }

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new WalletBalanceProviderException(
                Response::$statusTexts[$response->getStatusCode()] ?? 'unknown status',
                $response->getStatusCode()
            );
        }

        try {
            return json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new WalletBalanceProviderException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
