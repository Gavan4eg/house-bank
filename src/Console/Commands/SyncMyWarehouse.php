<?php

namespace Gavan4eg\HouseBank\Console\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Gavan4eg\HouseBank\Http\HttpClient;
use Gavan4eg\HouseBank\Models\Category;

class SyncMyWarehouse extends Command
{
    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:productFolders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация групп товаров с Мой склад.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->http = new HttpClient();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle(): int
    {
        /**
         * Product folder URL
         */
        $url = 'https://online.moysklad.ru/api/remap/1.2/entity/productfolder';

        /**
         * Получаем список папок
         */
        try {
            /**
             * Получаем данные новой группы
             */
            $data = $this->http->curl($url, 'GET');

            /**
             * Вывод сообщения в консоль
             */
            $this->echoConsole('Список папок загружен!');
        } catch (GuzzleException $e) {
            /**
             * Вывод сообщения в консоль
             */
            $this->echoConsole('Не удалось загрузить список папок :(', 'error');

            /**
             * Выход из команды
             */
            return false;
        }

        /**
         * Перебираем массив папок
         */
        collect($data->rows)->each(function ($productFolder) {
            /**
             * Ищем папку в БД
             */
            $category = Category::query()
                ->where('id_warehouse', $productFolder->id)
                ->first();
            /**
             * Проверяем папку на существование в БД
             */
            if ($category) {
                /**
                 * Если существует - обновляем
                 */
                $category->update([
                    'parent_id' => $this->parseUrl($productFolder->meta->href) ?? null,
                    'name' => $productFolder->name,
                    'archived' => $productFolder->archived
                ]);

                /**
                 * Вывод сообщения в консоль
                 */
                $this->echoConsole('Папка '.$productFolder->name.' найдена в БД и обновлена.');
            } else {
                Category::query()
                    ->create([
                        'id_warehouse' => $productFolder->id,
                        'parent_id' => $this->parseUrl($productFolder->meta->href) ?? null,
                        'name' => $productFolder->name,
                        'archived' => $productFolder->archived
                    ]);

                /**
                 * Вывод сообщения в консоль
                 */
                $this->echoConsole('Папка '.$productFolder->name.' добавлена в БД.', 'warning');
            }
        });

        return 0;
    }

    /**
     * Echo console
     *
     * @param $text
     * @param string $type
     * @return int
     */
    private function echoConsole($text, $type = 'success'): int
    {
        if ($type === 'success') {
            echo "\033[01;42m $text \033[0m".PHP_EOL;
        } elseif ($type === 'error') {
            echo "\033[01;31m $text \033[0m".PHP_EOL;
        } elseif ($type === 'warning') {
            echo "\033[01;43m $text \033[0m".PHP_EOL;
        }

        return 0;
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
