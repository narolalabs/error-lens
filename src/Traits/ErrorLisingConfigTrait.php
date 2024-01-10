<?php

namespace Narolalabs\ErrorLens\Traits;

trait ErrorLisingConfigTrait
{
    protected static array $queryString = [
        'today', 'yesterday', 'last-month', 'current-year', 'relevant'
    ];

    protected static string $defaultFilter = 'today';

    protected static int $perPageRecordLenght = 20;

    protected function getDefaultFilter(): string
    {
        return static::$defaultFilter;
    }

    protected function getPerPageRecordLenght(): int
    {
        return static::$perPageRecordLenght;
    }

    protected function getTitle($queryString)
    {
        $title = '';
        switch ($queryString) {
            case 'today': 
                $title = "Today's errors";
                break;
            case 'yesterday':
                $title = "Yesterday's errors";
                break;
            case 'last-month':
                $title = 'Last month errors';
                break;
            case 'current-year':
                $title = 'Current year errors';
                break;
            case 'relevant':
                $title = 'Last '.config('error-lens.error_preferences.showRelatedErrorsOfDays').' days relevant errors';
                break;
        }
        return $title;
    }

    protected function getFilterValue($view): string
    {
        $date = date('Y-m-d');
        switch ($view) {
            case 'today': 
                $date = date('Y-m-d');
                break;
            case 'yesterday':
                $date = date('Y-m-d', strtotime('yesterday'));
                break;
            case 'last-month':
                $date = date('Y-m', strtotime('last month'));
                break;
            case 'current-year':
                $date = date('Y');
                break;
        }
        return $date;
    }
}