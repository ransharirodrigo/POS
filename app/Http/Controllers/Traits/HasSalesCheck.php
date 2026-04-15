<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Redirect;
use Closure;
use Illuminate\Http\RedirectResponse;

trait HasSalesCheck
{
    protected function checkSales($model, string $route, string $messageKey, ?Closure $salesQuery = null): ?RedirectResponse
    {
        $query = $salesQuery 
            ? $salesQuery($model) 
            : $model->sales();
        
        if ($query->exists()) {
            return Redirect::route($route)->with('error', __("messages.{$messageKey}.cannot_delete"));
        }
        
        return null;
    }

    protected function deleteIfNoSales($model, string $route, string $messageKey, string $successMessage, ?Closure $salesQuery = null, ?Closure $beforeDelete = null): ?RedirectResponse
    {
        $error = $this->checkSales($model, $route, $messageKey, $salesQuery);
        
        if ($error) {
            return $error;
        }

        if ($beforeDelete) {
            $beforeDelete($model);
        }

        $model->delete();
        return Redirect::route($route)->with('success', $successMessage);
    }
}