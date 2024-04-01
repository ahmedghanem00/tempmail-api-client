<?php declare(strict_types=1);
/*
 * This file is part of the TempMailClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempMailClient;

use ahmedghanem00\TempMailClient\Exception\ResultErrorException;
use ahmedghanem00\TempMailClient\Model\ReceiverAddress;
use Arrayy\Arrayy;
use InvalidArgumentException;
use PHLAK\StrGen\Generator;
use SensitiveParameter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
class Client
{
    /**
     * @var string
     */
    public const RAPID_API_HOST = 'privatix-temp-mail-v1.p.rapidapi.com';

    /**
     * @var int
     */
    public const DEFAULT_HTTP_CLIENT_TIMEOUT = 15;

    /**
     * @var int
     */
    public const MAX_EMAIL_NAME_LENGTH = 10;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var array
     */
    private array $availableDomains;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __construct(
        #[SensitiveParameter]
        string               $apiKey,
        ?HttpClientInterface $httpClient = null
    )
    {
        $this->setHttpClient($httpClient ?? HttpClient::create());

        $this->setApiKey($apiKey);
        $this->setRapidAPIHost(self::RAPID_API_HOST);
        $this->setHttpClientTimeout(self::DEFAULT_HTTP_CLIENT_TIMEOUT);

        $this->retrieveAndCacheMailDomains();
    }

    /**
     * @param string $apiKey
     * @return void
     */
    public function setApiKey(#[SensitiveParameter] string $apiKey): void
    {
        $this->applyHttpClientOptions([
            'headers' => [
                'X-RapidAPI-Key' => $apiKey
            ]
        ]);
    }

    /**
     * @param array<string, string|array> $options
     * @return void
     */
    public function applyHttpClientOptions(array $options): void
    {
        $this->httpClient = $this->httpClient->withOptions($options);
    }

    /**
     * @param string $hostname
     * @return void
     */
    public function setRapidAPIHost(string $hostname): void
    {
        $this->applyHttpClientOptions([
            'headers' => [
                'X-RapidAPI-Host' => $hostname
            ],

            'base_uri' => 'https://' . $hostname
        ]);
    }

    /**
     * @param int $timeout
     * @return void
     */
    public function setHttpClientTimeout(int $timeout): void
    {
        $this->applyHttpClientOptions([
            'timeout' => $timeout
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveAndCacheMailDomains(): void
    {
        $this->availableDomains = $this->retrieveMailDomains();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveMailDomains(): array
    {
        $result = $this->httpClient->request('GET', 'request/domains/')->toArray(false);

        if ($resultMessage = @$result['message']) {
            throw new ResultErrorException($resultMessage);
        }

        return $result;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     */
    public function getCachedMailDomains(): array
    {
        return $this->availableDomains;
    }

    /**
     * @param int $emailNameLength
     * @return ReceiverAddress
     */
    public function generateFullyRandomReceiver(int $emailNameLength = 10): ReceiverAddress
    {
        $emailName = (new Generator())->lowerAlpha($emailNameLength);

        return $this->generateRandomReceiverFromEmailName($emailName);
    }

    /**
     * @param string $emailName
     * @return ReceiverAddress
     */
    public function generateRandomReceiverFromEmailName(string $emailName): ReceiverAddress
    {
        $maxAllowedLength = self::MAX_EMAIL_NAME_LENGTH;

        if ($emailName && (($emailNameLength = mb_strlen($emailName)) > $maxAllowedLength)) {
            throw new InvalidArgumentException("Given email name has a length ( $emailNameLength ) which is larger than the allowed ( $maxAllowedLength )");
        }

        $randomEmailDomain = Arrayy::createFromArray($this->availableDomains)->randomValue();

        return $this->loadReceiverAddress("{$emailName}{$randomEmailDomain}");
    }

    /**
     * @param string $emailAddress
     * @return ReceiverAddress
     */
    public function loadReceiverAddress(string $emailAddress): ReceiverAddress
    {
        return new ReceiverAddress($this->httpClient, $emailAddress);
    }
}
