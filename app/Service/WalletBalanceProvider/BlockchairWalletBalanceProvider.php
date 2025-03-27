<?php

declare(strict_types=1);

namespace App\Service\WalletBalanceProvider;

use App\Enum\Cryptocurrency;
use App\Exception\WalletBalanceProviderException;
use App\Models\Wallet;
use App\Util\ArrayUtil;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Этот класс не тестил на живую, т.к. апиключ раздобыть не получилось
 */
readonly class BlockchairWalletBalanceProvider implements WalletBalanceProviderInterface
{
    private const string API_URL = 'https://api.blockchair.com';
    private const array CURRENCY_TO_NETWORK_MAP = [
        Cryptocurrency::BTC => 'bitcoin',
        Cryptocurrency::LTC => 'litecoin',
    ];

    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private string $apiKey,
    ) {
    }

    public function supports(Wallet $wallet): bool
    {
        return array_key_exists($wallet->currency, self::CURRENCY_TO_NETWORK_MAP);
    }

    /**
     * @throws WalletBalanceProviderException
     */
    public function getBalance(Wallet $wallet): string
    {
        $network = self::CURRENCY_TO_NETWORK_MAP[$wallet->currency];
        $url = sprintf(
            '%s/%s/dashboards/address/%s?key=%s',
            self::API_URL,
            $network,
            $wallet->getAddress(),
            $this->apiKey
        );

        $response = $this->sendRequest($url);
        $balanceKey = sprintf('data.%s.address.balance', $wallet->getAddress());

        if (!ArrayUtil::hasByPath($response, $balanceKey)) {
            throw new WalletBalanceProviderException('Invalid Blockchair response data.');
        }

        // для денег удобнее использовать https://github.com/moneyphp/money - там задать инфо
        // "сколько знаков после запятой для такой-то валюты" и здесь это юзать
        return rtrim(
            rtrim(
                bcdiv((string) ArrayUtil::getByPath($response, $balanceKey), '100000000', 8),
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
