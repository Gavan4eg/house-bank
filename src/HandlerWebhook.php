<?php


namespace Gavan4eg\HouseBank;


use Illuminate\Http\JsonResponse;
use Gavan4eg\HouseBank\Models\Category;

class HandlerWebhook
{
    /**
     * @var Warehouse
     */
    private Warehouse $warehouse;

    /**
     * HandlerWebhook constructor.
     */
    public function __construct()
    {
        $this->warehouse = new Warehouse();
    }

    /**
     * Create webhook
     *
     * @param $data
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        /**
         * Получаем данные для БД
         */
        $data = $this->warehouse->create($data);

        /**
         * Проверяем ответ
         */
        if (!$data) {
            return response()->json('error http', 500);
        }

        /**
         * Сохраняем в БД
         */
        Category::query()->create($data);

        return response()->json('success');
    }

    /**
     * Update webhook
     *
     * @param array $data
     * @return JsonResponse
     */
    public function update(array $data): JsonResponse
    {
        /**
         * Получаем данные для БД
         */
        $data = $this->warehouse->update($data);

        /**
         * Проверяем ответ
         */
        if (!$data) {
            return response()->json('error http', 500);
        }

        /**
         * Поиск записи в БД
         */
        $updateCategory = Category::query()
            ->where('id_warehouse', $data['id_warehouse'])
            ->firstOrFail();

        /**
         * Обновляем запись
         */
        $updateCategory->update($data);

        return response()->json('success');
    }

    /**
     * Delete webhook
     *
     * @param array $data
     * @return JsonResponse
     */
    public function delete(array $data): JsonResponse
    {
        /**
         * Получаем данные для БД
         */
        $data = $this->warehouse->delete($data);

        /**
         * Проверяем ответ
         */
        if (!$data) {
            return response()->json('error http', 500);
        }

        /**
         * Поиск записи в БД
         */
        $deleteCategory = Category::query()
            ->where('id_warehouse', $data['id_warehouse'])
            ->firstOrFail();

        /**
         * Удаление записи из БД
         */
        $deleteCategory->delete();

        return response()->json('success');
    }
}
