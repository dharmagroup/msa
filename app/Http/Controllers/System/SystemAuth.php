<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\RoleGroup;
use Illuminate\Http\Request;
use App\Library\Authorization;
use App\Library\ModuleTree;
use App\Models\Role;
use App\Models\User;
class SystemAuth extends Controller
{
    public function _get_modules_(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if(!$auth->isLoggedIn){
            return Response()->json(['message' => config('messages.signin.token_error')],401,[],JSON_PRETTY_PRINT);
        }
        $auth->info();
        $module_data = $auth->getModule();
        $moduleTree = new ModuleTree();
        $modules = $moduleTree->getTree($module_data);
        $userdata= $auth->user;
        return Response()->json(['modules' => $modules,'userdata' => $userdata],200,[],JSON_PRETTY_PRINT);
    }

    public function _get_module_from_role(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if(!$auth->isLoggedIn){
            return Response()->json(['message' => config('messages.signin.token_error')],401,[],JSON_PRETTY_PRINT);
        }
        $role = $request->input('role');
        $module = new ModuleTree();
        $moduleTree = $module->_get_modules($role);
        return Response()->json(['modules' => $moduleTree],200,[],JSON_PRETTY_PRINT);
    }
   
    public function _get_roles_(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if(!$auth->isLoggedIn){
            return Response()->json(['message' => config('messages.signin.token_error')],401,[],JSON_PRETTY_PRINT);
        }
        $roles = Role::all();
        return Response()->json(['roles' => $roles],200,[],JSON_PRETTY_PRINT);
    }
    
    public function _update_role_groups_(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if(!$auth->isLoggedIn){
            return Response()->json(['message' => config('messages.signin.token_error')],401,[],JSON_PRETTY_PRINT);
        }
        
        $role_id = $request->input('role');
        $data    = $request->input('data');
        $record  = [];
        foreach($data as $item){
            array_push($record,['role_id' => $role_id,'module_id' => $item]);
        }
        $check = RoleGroup::where(['role_id' => $role_id]);
        $create = new RoleGroup();
        if($check->count() > 0){
           $delete = $check->delete();
           $create = $create->make()->createMany($record);
        }
        else{
            $create = $create->make()->createMany($record);
        }
        return Response()->json(['message' => config('messages/.systemauth.role_update_success')],200,[],JSON_PRETTY_PRINT);
    }
    

    public function _delete_role_(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if(!$auth->isLoggedIn){
            return Response()->json(['message' => config('messages.signin.token_error')],401,[],JSON_PRETTY_PRINT);
        }
        
        $role_id = $request->input('role');
        $delete = Role::where(['role_id' => $role_id]);
        if($delete->count() > 0) $delete->delete();
        $delete2 = RoleGroup::where(['role_id' => $role_id]);
        if($delete2->count() > 0) $delete2->delete();
        $delete3 = \App\Models\Authorization::where(['role_group_id' => $role_id]);
        if($delete3->count() > 0) $delete3->delete();
        if($delete){
            return Response()->json(['message' => config('messages.systemauth.role_delete_succcess')],200,[],JSON_PRETTY_PRINT);
        }
        return Response()->json(['message' => config('messages.systemauth.role_delete_error')],401,[],JSON_PRETTY_PRINT);
    }

    


    public function _add_role_(Request $request){
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if(!$auth->isLoggedIn){
            return Response()->json(['message' => config('messages.signin.token_error')],401,[],JSON_PRETTY_PRINT);
        }
        $role = $request->input('role');
        $create = Role::create(['role_name' => $role]);
        if($create){
            return Response()->json(['message' => config('messages.systemauth.add_role.success')],200,[],JSON_PRETTY_PRINT);
        }
        return Response()->json(['message' => config('messages.systemauth.add_role.error')],401,[],JSON_PRETTY_PRINT);
    }

    
}
