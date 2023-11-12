<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QuerySortAndFilter
{
    protected static array $sortKeys;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            if ($field === 'filter') {
                foreach ($value as $subFieldKey => $subFieldValue) {
                    $method = $field . ucfirst($subFieldKey);
                    if (method_exists($this, $method)) {
                        call_user_func_array([$this, $method], [$subFieldKey => $subFieldValue]);
                    }
                }
            } else if ($field === 'sort') {
                $method = Str::camel($field);
                if (method_exists($this, $method)) {
                    call_user_func_array([$this, $method], ['sort' => $value]);
                }
            } else {
                $method = Str::camel($field);
                if (method_exists($this, $method)) {
                    call_user_func_array([$this, $method], $value);
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function fields(): array
    {
        $params = $this->request->all();
        if (is_array($params)) {
            return $params;
        }
        return array_filter(array_map('trim', $this->request->all()), function ($value) {
            return $value !== null;
        });
    }

    public function sort(array $sort): void
    {
        foreach ($sort as $key => $order) {
            $this->builder->orderBy(Str::snake($key, '_'), $order);
        }
    }
}
