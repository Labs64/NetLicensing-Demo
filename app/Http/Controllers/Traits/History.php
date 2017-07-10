<?php

namespace App\Http\Controllers\Traits;

use App\Helpers\DotCollection\DotCollection;
use Carbon\Carbon;

trait History
{
    protected $historyStorage;

    protected function createHistory($data)
    {
        $id = uniqid();

        $history = dot_collect($data);

        $history->put('id', $id);
        $history->put('date', Carbon::now());

        return $history;
    }

    protected function saveHistory(DotCollection $history, $storage = null)
    {
        $storage = $this->getStorage($storage);

        $histories = $this->getHistories($storage);

        $historyMaxItems = config('nlic.history.max_items');

        if ($historyMaxItems && $histories->count() >= $historyMaxItems) {
            $histories = $histories->splice($histories->count() + 1 - $historyMaxItems);
        }

        $histories->push($history);

        \Cache::put($storage, $histories, config('nlic.history.lifetime'));
    }

    protected function getHistory($id, $storage = null)
    {
        $storage = $this->getStorage($storage);

        $histories = $this->getHistories($storage);

        return dot_collect($histories->where('id', $id)->first());
    }

    protected function getHistories($storage = null)
    {
        $storage = $this->getStorage($storage);

        return \Cache::get($storage, dot_collect());
    }

    private function getStorage($storage = null)
    {
        $storage = !is_null($storage) ? $storage : $this->historyStorage;
        $storage = $storage ? $storage : snake_case(str_replace('\\', '', get_class($this)));

        return $storage;
    }
}