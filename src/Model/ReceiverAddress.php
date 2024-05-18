<?php

declare(strict_types=1);
/*
 * This file is part of the TempMailClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempMailClient\Model;

use InvalidArgumentException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
readonly class ReceiverAddress
{
    /**
     * @var string
     */
    private string $emailName;

    /**
     * @var string
     */
    private string $emailDomain;

    /**
     * @param HttpClientInterface $httpClient
     * @param string $emailAddress
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $emailAddress
    ) {
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("the provided email address ( $emailAddress ) isn't in a valid format");
        }

        [$this->emailName, $this->emailDomain] = explode("@", $emailAddress);
    }

    /**
     * @return string
     */
    public function getEmailName(): string
    {
        return $this->emailName;
    }

    /**
     * @return string
     */
    public function getEmailDomain(): string
    {
        return $this->emailDomain;
    }

    /**
     * @return string
     */
    public function getFullEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return Inbox
     */
    public function inbox(): Inbox
    {
        return new Inbox($this->httpClient, $this->emailAddress);
    }
}
