<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Routing\Controller;


class ApiController extends Controller
{
    //
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
