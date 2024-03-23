# TempMail API Client

An API client for Temp-Mail service ( https://rapidapi.com/Privatix/api/temp-mail )

## Installation

````
composer require ahmedghanem00/tempmail-api-client
````

## Usage

#### Client Initialization :

````php
use ahmedghanem00\TempMailClient\Client;

$client = new Client('YOUR_API_TOKEN');
````

#### Get Available Domains

Retrieve a fresh data from the server:

````php
$client->retrieveMailDomains();
````

Get the cached domains in the client (Domains are cached when client initialized for the first time)

````php
$client->getCachedMailDomains();
````

#### Generate a Random Email

````php
$receiver = $client->generateFullyRandomReceiver();
## OR
$receiver = $client->generateRandomReceiverFromEmailName("my-random-email");

echo $receiver->getFullEmailAddress(); // string "bdmhnjbtyj@nuclene.com" OR "my-random-email@nuclene.com"
echo $receiver->getEmailName(); // string "bdmhnjbtyj" OR "my-random-email"
echo $receiver->getEmailDomain(); // string "nuclene.com"
````

#### Get email messages

````php
$messages = $receiver->inbox()->retrieveAll();

/* @var $message \ahmedghanem00\TempMailClient\Model\Message */
foreach ($messages as $message) {
    echo $message->getSubject(); // string
    echo $message->getText(); // string
    
    echo $message->getSenderName(); // string "Joe"
    echo $message->getSenderEmail(); // string
    
    echo $message->getReceiveTimestamp(); // float
    echo $message->getHtml(); // string
    echo $message->getPreview(); // string
    
    # Message id
    echo $message->getServiceInternalId(); // string
    
    /* @var $attachment \ahmedghanem00\TempMailClient\Model\Attachment */
    foreach ($message->getAttachments() as $attachment) {
        echo $attachment->getName(); // string
        echo $attachment->getContentType(); // string
        echo $attachment->getSize(); // int
        echo $attachment->getContent(); // string
    }
    
}
````

#### Retrieve a Specific Message

````php
$message = $receiver->inbox()->retrieveMessage(MessageId: "dk4kdkmv");
````

#### Delete a Specific Message

````php
$receiver->inbox()->deleteMessage(MessageId: "dk4kdkmv");
````

## LICENSE

Package is licensed under the [MIT License](http://opensource.org/licenses/MIT). For more info, You can take a look at the [License File](LICENSE)
