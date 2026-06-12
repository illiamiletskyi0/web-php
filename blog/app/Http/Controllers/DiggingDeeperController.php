<?php

namespace App\Http\Controllers;


use App\Jobs\ProcessVideoJob;
use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;
use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    /**
     * Базова інформація 
     * @url https://laravel.com/docs/13.x/collections#introduction
     * 
     * Довідкова інформація
     * @url https://laravel.com/api/13.x/Illuminate/Support/Collection.html
     * 
     * Варіант колеції для моделі eloquent
     * @url https://laravel.com/api/13.x/Illuminate/Database/Eloquent/Collection.html
     * 
     */

    public function collections()
    {
        $eloquentCollection = BlogPost::withTrashed()->get();

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());

        $result['first'] = $collection->first(); // вибираємо 1 елемент
        $result['last'] = $collection->last();  // вибираємо останній елемент
        
        $result['where']['data'] = $collection  
            ->where('category_id', 10)  // вибираємо елементи з категорією 10
            ->values()  // беремо лише значення без ключів
            ->keyBy('id');  // прирівнюємо id з бд з ключем масива

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        $result['where_first'] = $collection
            ->firstWhere('created_at', '>' , '2020-02-24 03:46:16');

        // Базова змінна не змінюється. Вертаємо змінено версію.
        $result['map']['all'] = $collection->map(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);

            return $newItem;
        });

        $result['map']['not_exists'] = $result['map']['all']->where('exists', false)->values()->keyBy('item_id');  // витягаємо видалені елементи

        // Базова змінна змінюється (трансформується).
        $collection->transform(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });
        
        $newItem = new \stdClass;
        $newItem->id = 9999;
        $newItem->created_at = Carbon::now();
        
        $newItem2 = new \stdClass;
        $newItem2->id = 8888;
        $newItem2->created_at = Carbon::now();

        // Фільтрація
        $filtered = $collection->filter(function ($item) {
            if (!isset($item->created_at)) {
                return false;
            }

            $byDay = $item->created_at->isFriday();   // питаємо Carbon
            $byDate = $item->created_at->day == 11;

            return $byDay && $byDate;
        });

        // Додаємо елемент в початок/кінець колекції після фільтрації
        $newItemFirst = $collection->prepend($newItem)->first(); // додали в початок
        $newItemLast = $collection->push($newItem2)->last(); // додали в кінець
        $pulledItem = $collection->pull(1); // забрали з першим ключем

        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at')->values();
        $sortedDescCollection = $collection->sortByDesc('item_id')->values();

        return response()->json([
            'first' => $result['first'],
            'last' => $result['last'],
            'where' => $result['where'],
            'where_first' => $result['where_first'],
            'map' => [
                'all' => $result['map']['all'],
                'not_exists' => $result['map']['not_exists'],
            ],
            'filtered' => $filtered->values(),
            'collection' => $collection,
            'new_item_first' => $newItemFirst,
            'new_item_last' => $newItemLast,
            'pulled_item' => $pulledItem,
            'sorted_simple' => $sortedSimpleCollection,
            'sorted_asc' => $sortedAscCollection,
            'sorted_desc' => $sortedDescCollection,
        ]);
    }

 
    public function processVideo()
    {
        ProcessVideoJob::dispatch();
            // Відкладення виконання завдання від моменту потрапляння в чергу.
            // Не впливає на паузу між спробами виконання завдання.
            //->delay(10)
            //->onQueue('name_of_queue')
    }
    
    /**
     * @link http://localhost:8000/digging_deeper/prepare-catalog
     *
     * php artisan queue:listen --queue=generate-catalog --tries=3 --delay=10
     */
    public function prepareCatalog()
    {
        GenerateCatalogMainJob::dispatch();
    }


}