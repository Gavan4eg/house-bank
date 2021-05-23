<?php


namespace Gavan4eg\HouseBank\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    /**
     * Токен приложения
     *
     * @var string
     */
    private string $token;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * HttpClient constructor.
     * @param string $token
     */
    public function __construct()
    {
        $this->token = config('my-warehouse.token');

        /**
         * Иницилизируем клиент
         */
        $this->client = new Client();
    }

    /**
     * CURL
     *
     * @param string $url
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function curl(string $url, string $method, array $params = [])
    {
        $result = $this->client->request(
            $method, $url, $params + [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type' => 'application/json'
                ]
            ]
        )
            ->getBody()
            ->getContents();

        return json_decode($result);
    }
}
