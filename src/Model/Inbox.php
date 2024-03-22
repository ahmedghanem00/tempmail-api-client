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

use ahmedghanem00\TempMailClient\Exception\ResultErrorException;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
readonly class Inbox
{
    /**
     * @param HttpClientInterface $httpClient
     * @param string $emailAddress
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private string              $emailAddress
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveAll(): ArrayCollection
    {
        $emailAddressHash = md5($this->emailAddress);
        $jsonResult = $this->httpClient->request('GET', "request/mail/id/$emailAddressHash/")->toArray(false);

        $this->checkResultForError($jsonResult);

        return new ArrayCollection(
            array_map(
                function (array $message) {
                    return new Message($message);
                },
                $jsonResult
            )
        );
    }

    /**
     * @param array $jsonResult
     * @return void
     */
    private function checkResultForError(array $jsonResult): void
    {
        if ($errorMsg = @$jsonResult['error']) {
            throw new ResultErrorException($errorMsg);
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function retrieveMessageSource(string $messageId): array
    {
        $jsonResult = $this->httpClient->request("GET", "request/source/id/$messageId/")->toArray(false);

        $this->checkResultForError($jsonResult);

        return $jsonResult;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveMessage(string $messageId): Message
    {
        $jsonResult = $this->httpClient->request("GET", "request/one_mail/id/$messageId/")->toArray(false);

        $this->checkResultForError($jsonResult);

        return new Message($jsonResult);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveAttachmentsForMessage(string $messageId): ArrayCollection
    {
        $jsonResult = $this->httpClient->request("GET", "/request/atchmnts/id/$messageId/")->toArray(false);

        $this->checkResultForError($jsonResult);

        return new ArrayCollection(
            array_map(
                function (array $message) {
                    return new Attachment($message);
                },
                $jsonResult
            )
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function retrieveMessageAttachment(string $messageId, string $attachmentId): Attachment
    {
        $jsonResult = $this->httpClient->request("GET", "/request/one_attachment/id/$messageId/$attachmentId/")->toArray(false);

        $this->checkResultForError($jsonResult);

        return new Attachment($jsonResult);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function deleteMessage(string $messageId): void
    {
        $jsonResult = $this->httpClient->request("GET", "/request/delete/id/$messageId/")->toArray(false);

        $this->checkResultForError($jsonResult);

        if (($resultMsg = @$jsonResult['result']) !== 'success') {
            throw new ResultErrorException("Failed operation. Received result from endpoint ( $resultMsg )");
        }
    }
}
