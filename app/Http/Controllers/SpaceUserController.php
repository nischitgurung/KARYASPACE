<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpaceUserController extends Controller
{
    public function isAdminOf(Space $space)
{
    return $this->id === $space->user_id;
}


}
