<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HomeOwnerService;

class HomeownerController extends Controller
{
    protected $homeOwnerService;

    public function __construct(HomeOwnerService $homeOwnerService)
    {
        $this->homeOwnerService = $homeOwnerService;
    }
    
    public function parseCSV(Request $request)
    {
        return $this->homeOwnerService->parseCSV($request);
    }

    public function parseName($name)
    {
        return $this->homeOwnerService->parseName($name);
    }

    
}
