<?php


namespace Gavan4eg\HouseBank;

use GuzzleHttp\Exception\GuzzleException;
use Gavan4eg\HouseBank\Http\HttpClient;
use Gavan4eg\HouseBank\Logs\Log;

class Warehouse
{
    /**
     * Токен приложения
     *
     * @var string
     */
    private string $token;

    /**
     * @var Log
     */
    private Log $logger;

    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * Warehouse constructor.
     * @param string $token
     */
    public function __construct()
    {
        $this->token = config('my-warehouse.token');

        $this->http = new HttpClient();
        $this->logger = new Log();
    }

    /**
     * Create
     *
     * @param array $data
     * @return array|false
     */
    public function create(array $data)
    {
        /**
         * Логируем запрос
         */
        $this->logger->log($data);

        /**
         * URL созданной группы
         */
        $url = $data['events'][0]['meta']['href'];

        try {
            /**
             * Получаем данные новой группы
             */
            $newGroup = $this->http->curl($url, 'GET');

            /**
             * Записываем ответ
             */
            $this->logger->log($newGroup);
        } catch (GuzzleException $e) {
            /**
             * Записывам ошибку в лог
             */
            $this->logger->error($e);

            return false;
        }

        if (!empty($newGroup->productFolder)) {
            /**
             * Получаем uuid родительской группы
             */
            $parent = $this->parseUrl($newGroup->productFolder->meta->href);
        }

        return [
            'id_warehouse' => $newGroup->id,
            'parent_id' => isset($parent) ? $parent : null,
            'name' => $newGroup->name
        ];
    }

    /**
     * Update
     *
     * @param array $data
     * @return array|false
     */
    public function update(array $data)
    {
        /**
         * Логируем запрос
         */
        $this->logger->log($data);

        /**
         * URL редактируемой группы
         */
        $url = $data['events'][0]['meta']['href'];

        try {
            /**
             * Получаем данные обновляемой группы
             */
            $updateGroup = $this->http->curl($url, 'GET');

            /**
             * Записываем ответ
             */
            $this->logger->log($updateGroup);
        } catch (GuzzleException $e) {
            /**
             * Записывам ошибку в лог
             */
            $this->logger->error($e);

            return false;
        }

        if (!empty($updateGroup->productFolder)) {
            /**
             * Получаем uuid родительской группы
             */
            $parent = $this->parseUrl($updateGroup->productFolder->meta->href);
        }

        return [
            'id_warehouse' => $updateGroup->id,
            'parent_id' => isset($parent) ? $parent : null,
            'name' => $updateGroup->name,
            'archived' => $updateGroup->archived
        ];
    }

    /**
     * Delete
     *
     * @param array $data
     * @return string[]|string[][]
     */
    public function delete(array $data): array
    {
        /**
         * Логируем запрос
         */
        $this->logger->log($data);

        /**
         * Получаем uuid удаленной группы
         */
        $id = $this->parseUrl($data['events'][0]['meta']['href']);

        return [
            'id_warehouse' => $id
        ];
    }

    /**
     * Парсер URL
     *
     * @param $url
     * @return string|string[]
     */
    private function parseUrl($url)
    {
        return pathinfo(
            parse_url($url)['path'], PATHINFO_FILENAME
        );
    }
}
