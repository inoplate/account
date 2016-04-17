<?php

namespace Inoplate\Account\Http\Controllers;

use Inoplate\Foundation\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getIndex()
    {
        return view('inoplate-account::dashboard.index');
    }
}