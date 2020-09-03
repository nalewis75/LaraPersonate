<?php

namespace Octopy\LaraPersonate\AuthorizationDrivers;

/**
 * Class DefaultDriver
 *
 * @package Octopy\LaraPersonate\AuthorizationDrivers
 */
class DefaultAuthorizationDriver extends AuthorizationDriver
{
    /**
     * @return mixed|void
     */
    public function handle($request = null)
    {
        if(isset($request) && $request->has('search')) {
            return $this->reMap(
                $this->model->where(function($query) use ($request) {

                    $fields = config('impersonate.search_fields', 'name');
                    foreach($fields as $field) {
                        $query = $query->orWhere($field, 'ilike', '%' . trim($request->input('search')) . '%');
                    }

                    return $query;
                })->limit(config('impersonate.limit', 3))->get()
            );
        }

        return $this->reMap(
            $this->model->limit(config('impersonate.limit', 3))->get()
        );
    }
}
