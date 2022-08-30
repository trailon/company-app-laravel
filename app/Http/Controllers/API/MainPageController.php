<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
class MainPageController extends BaseController
{
    public function mainPageData()
    {
        return $this->sendResponse("Haha","Evet!");

    }

}
