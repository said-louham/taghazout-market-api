<?php

use Carbon\Carbon;

function _toDate($date)
{
    return Carbon::parse($date)->format('Y-m-d');
}

function _paginatePages($count = 0)
{
    $perPage = (int) (request()->get('per_page')) ?: 10;

    return $count > 0 && $perPage == -1 ? $count : $perPage;
}
