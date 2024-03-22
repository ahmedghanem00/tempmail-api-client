<?php declare(strict_types=1);
/*
 * This file is part of the TempMailClient package.
 *
 * (c) Ahmed Ghanem <ahmedghanem7361@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ahmedghanem00\TempMailClient\Model;

/**
 *
 */
readonly class Attachment
{
    /**
     * @param array $attachmentData
     */
    public function __construct(
        private array $attachmentData
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->attachmentData['name'];
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->attachmentData['content'];
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->attachmentData['contentType'];
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->attachmentData['size'];
    }

    /**
     * @return string
     */
    public function getCID(): string
    {
        return $this->attachmentData['cid'];
    }
}
