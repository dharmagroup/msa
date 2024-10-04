<?php

namespace App\Library;

use App\Models\Employee;
use App\Models\User;
use App\Models\Token;
use App\Models\RoleGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Authorization
{
    public $token;
    public $user;
    public $isLoggedIn = false;

    public function fromToken($token)
    {
        $this->token = Token::where(['token' => $token, 'status' => 'active'])->first();
        $this->isLoggedIn = $this->token !== null;
        return $this;
    }

    public function info() {
        if (!$this->isLoggedIn) {
            return null; // Token tidak valid
        }
    
        $this->user = User::where('users.user_id', $this->token->user_id)
            ->join('employees', 'employees.employee_id', '=', 'users.employee_id')
            ->join('authorizations', 'authorizations.user_id', '=', 'users.user_id')
            ->where('employees.status', 'active')
            ->select('employees.*', 'authorizations.role_group_id', 'users.user_type')
            ->first();
    
        if (!$this->user) {
            \Log::error('User not found for token user_id: ' . $this->token->user_id); // Log jika pengguna tidak ditemukan
        }
    
        return $this;
    }
    

    public function getModule() {
        if (!$this->user) {
            return null; // User tidak ditemukan
        }
    
        $modules = RoleGroup::where('role_groups.role_id', $this->user->role_group_id)
            ->join('modules', 'modules.module_id', '=', 'role_groups.module_id')
            ->select('modules.module_id','modules.module_name','modules.module_parent','modules.url','modules.icon')
            ->orderBy('modules.module_id','asc')
            ->get(); // Ambil semua modul
    
        if ($modules->isEmpty()) {
            \Log::warning('No modules found for role_group_id: ' . $this->user->role_group_id); // Log jika tidak ada modul
        }
    
        return $modules;
    }
    

    public function check($username, $password)
    {
        $user = User::where('username', $username)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Generate token
            $employee = Employee::where(['employee_id' => $user->employee_id,'status' => 'active'])->count();
            if($employee > 0){
                $tokenString = Str::random(60);
                $token = Token::create([
                    'token' => $tokenString,
                    'user_id' => $user->user_id,
                    'status' => 'active'
                ]);
                return response()->json([
                    'message' => config('messages.signin.success'),
                    'token' => $token->token,
                   
                ]);
            }
            return response()->json(['message' => config('messages.signin.inactive_account'),], 401);

        } else {
            // Password tidak valid
            return response()->json(['message' => config('messages.signin.error'),], 401);
        }
    }

    public function destroy($tokenString)
    {
        $token = Token::where('token', $tokenString);
        if ($token->count() > 0) {
            $token->delete();
            return response()->json(['message' => config('messages.signout.success'),]);
        } else {
            return response()->json(['message' => config('messages.signout.error')], 404);
        }
    }

    public function permissions($url, $modules) {
        // Ambil semua nilai 'url' dari koleksi $modules
        $urls = $modules->pluck('url')->toArray();
    
        // Cek apakah $url ada dalam array $urls
        return in_array($url, $urls);
    }
    
}
