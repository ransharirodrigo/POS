<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Redirect;
use Closure;
use Illuminate\Http\RedirectResponse;

trait CanDelete
{
    protected function canDelete($model, string $relation, string $route, string $messageKey): ?RedirectResponse
    {
        if ($model->$relation()->exists()) {
            return Redirect::route($route)->with('error', __("messages.{$messageKey}.cannot_delete"));
        }
        
        return null;
    }

    protected function deleteIfNoRelated($model, string $relation, string $route, string $messageKey, string $successMessage, ?Closure $beforeDelete = null): ?RedirectResponse
    {
        $error = $this->canDelete($model, $relation, $route, $messageKey);
        
        if ($error) {
            return $error;
        }

        if ($beforeDelete) {
            $beforeDelete($model);
        }

        $model->delete();
        return Redirect::route($route)->with('success', $successMessage);
    }

    protected function deleteIfNoSales($model, string $route, string $messageKey, string $successMessage, ?Closure $beforeDelete = null): ?RedirectResponse
    {
        if ($model->sales()->exists()) {
            return Redirect::route($route)->with('error', __("messages.{$messageKey}.cannot_delete"));
        }

        if ($beforeDelete) {
            $beforeDelete($model);
        }

        $model->delete();
        return Redirect::route($route)->with('success', $successMessage);
    }

    protected function deleteWithQuery($model, Closure $relationQuery, string $route, string $messageKey, string $successMessage, ?Closure $beforeDelete = null): ?RedirectResponse
    {
        if ($relationQuery($model)->exists()) {
            return Redirect::route($route)->with('error', __("messages.{$messageKey}.cannot_delete"));
        }

        if ($beforeDelete) {
            $beforeDelete($model);
        }

        $model->delete();
        return Redirect::route($route)->with('success', $successMessage);
    }
}