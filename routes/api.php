<?php

use App\Http\Controllers\Modules\Manufacture\Andons;
use App\Http\Controllers\System\SystemAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\SessionsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('signin', [SessionsController::class, '_signin_']);
Route::post('signout', [SessionsController::class, '_signout_']);
Route::post('modules', [SystemAuth::class, '_get_modules_']);
Route::post('modules-from-role', [SystemAuth::class, '_get_module_from_role']);
Route::post('roles', [SystemAuth::class, '_get_roles_']);
Route::post('update-role', [SystemAuth::class, '_update_role_groups_']);
Route::post('delete-role', [SystemAuth::class, '_delete_role_']);
Route::post('add-role', [SystemAuth::class, '_add_role_']);
Route::prefix('manufacture')->group(function () {
    Route::prefix('andon')->group(function () {
        Route::post('layout',[Andons::class, '_get_layout_data']);
        Route::post('layout-trigger',[Andons::class, '_get_layout_data_trigger']);
        Route::post('create',[Andons::class, '_create_alarm']);
        Route::post('start-repair',[Andons::class, '_create_making_repair']);
        Route::get('start-repair-automatic',[Andons::class, '_create_making_repair_automatic']);
        Route::post('reports',[Andons::class, '_get_reports_']);
        Route::post('reports-excel',[Andons::class, '_get_reports_excel_']);
    });
});