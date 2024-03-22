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

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 */
readonly class Message
{
    /**
     * @param array $messageData
     */
    public function __construct(
        private array $messageData
    )
    {

    }

    /**
     * @return string
     */
    public function getServiceInternalId(): string
    {
        return $this->messageData['mail_id'];
    }

    /**
     * @return string
     */
    public function getReceiverEmailHash(): string
    {
        return $this->messageData['mail_address_id'];
    }

    /**
     * @return string
     */
    public function getSenderRawEmail(): string
    {
        return $this->messageData['mail_from'];
    }

    /**
     * @return string
     */
    public function getSenderName(): string
    {
        return ""; // TODO
    }

    /**
     * @return string
     */
    public function getSenderEmail(): string
    {
        return ""; // TODO
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->messageData['mail_subject'];
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->messageData['mail_preview'];
    }

    /**
     * @return string
     */
    public function getTextOnly(): string
    {
        return $this->messageData['mail_text_only'];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->messageData['mail_text'];
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->messageData['mail_html'];
    }

    /**
     * @return float
     */
    public function getReceiveTimestamp(): float
    {
        return $this->messageData['mail_timestamp'];
    }

    /**
     * @return int
     */
    public function getAttachmentsCount(): int
    {
        return $this->messageData['mail_attachments_count'];
    }

    /**
     * @return ArrayCollection
     */
    public function getAttachments(): ArrayCollection
    {
        return new ArrayCollection(
            array_map(
                function (array $attachment) {
                    return new AttachmentInfo($attachment);
                },
                $this->messageData['mail_attachments']['attachment']
            )
        );
    }
}
