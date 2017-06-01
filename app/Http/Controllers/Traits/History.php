<?php

namespace App\Http\Controllers\Traits;

use App\Helpers\DotCollection\DotCollection;
use Carbon\Carbon;

trait History
{
    protected function createHistory($data)
    {
        $id = uniqid();

        $history = dot_collect($data);

        $history->put('id', $id);
        $history->put('date', Carbon::now());

        return $history;
    }

    protected function saveHistory(DotCollection $history, $storage)
    {
        $histories = $this->getHistories($storage);

        if (config('nlic.history.max_items') && $histories->count() >= config('nlic.history.max_items')) {
            $histories = $histories->splice($histories->count() + 1 - config('nlic.history.max_items'));
        }

        $histories->push($history);

        \Cache::put($storage, $histories, config('nlic.history.lifetime'));
    }

    protected function getHistory($id, $storage)
    {
        $histories = $this->getHistories($storage);

        return dot_collect($histories->where('id', $id)->first());
    }

    protected function getHistories($storage)
    {
        return \Cache::get($storage, dot_collect());
    }
}