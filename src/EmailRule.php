<?php

namespace KaniRobinson\LaravelVerifyEmailOrg;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class EmailRule
{
    /**
     * Guzzle Client Instance
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Verify Email API Key
     *
     * @var string
     */
    protected $key;

    /**
     * Logger Interface Instance
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * Create a new Email Rule instance.
     *
     * @param Client $client
     * @param LoggerInterface $log
     * @param string $key
     */
    public function __construct(Client $client, LoggerInterface $log, $key = '')
    {
        $this->client = $client;
        $this->key = $key;
        $this->log = $log;
    }

    /**
     * Check if the Email is validated and verified
     *
     * @return bool|string Returns true if valid or an error message if invalid
     */
    public function validate($attribute, $value, $parameters)
    {
        $uri = $this->buildApiUri($value);

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }   

        if(is_null($this->key)) {
            return false;
        }

        return $this->checkValidation($uri);
    }

    /**
     * Build API URI
     *
     * @param string $value
     * @return void
     */
    protected function buildApiUri(string $value)
    {
        return 'https://app.verify-email.org/api/v1/' . $this->key . '/verify/' . $value;
    }

    /**
     * Get Response from Validation
     *
     * @param string $uri
     * @return void
     */
    protected function checkValidation(string $uri)
    {
        try {
            $response = $this->client->get($uri);
        } catch (ConnectException $e) {
            return false;
        } catch (ClientException $e) {
            return false;
        }
        
        if (!$body = json_decode($response->getBody())) {
            return false;
        }

        return (boolean) $body->status;
    }
}