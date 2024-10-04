<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Library\Authorization;
use App\Library\ModuleTree;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function _signin_(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        $authorization = new Authorization();
        return $authorization->check($username, $password);
    }

    public function _signout_(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        return $authorization->destroy($token);
    }

   

}
