<?php

namespace App\Http\Controllers\Admin;
class ErrorController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('admin.error');
    }
}
