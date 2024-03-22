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
readonly class AttachmentInfo
{
    /**
     * @param array $attachmentInfo
     */
    public function __construct(
        private array $attachmentInfo
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->attachmentInfo['_id'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->attachmentInfo['filename'];
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->attachmentInfo['mimetype'];
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->attachmentInfo['size'];
    }

    /**
     * @return string
     */
    public function getCID(): string
    {
        return $this->attachmentInfo['cid'];
    }
}