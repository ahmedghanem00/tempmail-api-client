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

namespace ahmedghanem00\TempMailClient\Exception;

use RuntimeException;
use Throwable;

/**
 *
 */
class TempMailClientException extends RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $additionalData
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null,
        private array $additionalData = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getAdditionalData(string $key = null): mixed
    {
        return $key ? $this->additionalData[$key] : $this->additionalData;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addToAdditionalData(string $key, mixed $value): void
    {
        $this->additionalData[$key] = $value;
    }
}
