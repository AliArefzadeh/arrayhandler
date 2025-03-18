<?php

namespace AliAref\ArrayFilterable;

use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class DataHandler
{
    public function handle($summary, $params = [], $request = null)
    {
        $params = $params ?: request()->query();
        $request = $request ?: request()->query();

        $this->filters($summary, $params);
        $this->orderBy($summary, $params);
        return $this->returnPaginated($summary, $request);
    }


    public function filters(&$summary, $params)
    {
        foreach ($params as $key => $value) {
            if (!in_array($key, ['customFilter', 'orderBy', 'perPage', 'page', 'notPaginated'])) {
                $summaryKeys = collect($summary)->collapse()->keys()->unique()->values()->toArray();
                if (!!$summaryKeys && is_array($summaryKeys) && in_array($key, $summaryKeys)) {
                    $summary = collect($summary)->where($key, $value)->toArray();
                }
            }
        }

        if (array_key_exists('customFilter', $params) && $params['customFilter'] && count($params['customFilter']) > 0) {
            $this->customFilter($summary, $params['customFilter']);
        }
    }

    public function orderBy(&$summary, $params)
    {
        if (array_key_exists('orderBy', $params) && !!$params['orderBy']) {
            $summaryKeys = collect($summary)->collapse()->keys()->unique()->values()->toArray();
            $value = Str::snake($params['orderBy']);

            if (!!$summaryKeys && is_array($summaryKeys) && in_array($value, $summaryKeys)) {
                $summary = collect($summary)->sortByDesc($value)->values()->toArray();
            }
        }
    }

    public function returnPaginated($input, $request)
    {
        if ((isset($request['notPaginated']) && $request['notPaginated'] == "0") || !isset($request['notPaginated'])) {
            $page = request()->get('page', 1);
            $perPage = $request->query()['perpage'] ?? 15;

            $paginatedItems = collect($input)->forPage($page, $perPage);

            return new LengthAwarePaginator(
                $paginatedItems,
                collect($input)->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            return $input;
        }
    }

    // Optional: Define your custom filter logic if needed
    protected function customFilter(&$summary, $filters)
    {
        // Implement custom filter logic here if needed
    }
}
