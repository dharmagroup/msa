<?php

namespace App\Library;

use App\Models\Module;
use App\Models\RoleGroup;

class ModuleTree
{
    public function getTree($modules)
    {
        return $this->buildTree($modules);
    }

    private function buildTree($modules, $parentId = null)
    {
        $branch = [];

        foreach ($modules as $module) {
            // Check if this module's parent matches the current parentId
            if ($module->module_parent === $parentId) {
                // Ensure module_name and url are set correctly
                $module->module_parent = $module->module_parent ?? ""; // Default to empty string if null
                $module->url = $module->url ?? ""; // Default to empty string if null

                // Recursively get children
                $children = $this->buildTree($modules, $module->module_id);
                if ($children) {
                    $module->children = $children; // Assign children if any
                    $module->have_children = true;
                } else {
                    $module->children = []; // Ensure children is an empty array
                    $module->have_children = false;
                }
                $branch[] = $module; // Add the module to the branch
            }
        }

        return $branch; // Return the constructed branch
    }

    public function _get_modules($role_group_id)
    {
         $rolegroup = RoleGroup::where(['role_id' => $role_group_id])->select('module_id')->get();       
         $modules   = Module::select('module_id','module_name','module_parent','url','icon')->get();
         return $this->_getTree_($modules,$rolegroup);         
    }

    public function _getTree_($modules, $role_groups)
    {
        return $this->_buildTree_($modules, $role_groups);
    }

    private function _buildTree_($modules, $role_groups, $parentId = null)
    {
        $branch = [];
    
        // Ubah role_groups menjadi array menggunakan pluck
        $authModuleIds = $role_groups->pluck('module_id')->toArray();
    
        foreach ($modules as $module) {
            // Cek jika parentId dari modul ini cocok dengan parentId saat ini
            if ($module->module_parent === $parentId) {
                // Set field checked berdasarkan apakah module_id ada dalam data authorization
                $module->checked = in_array($module->module_id, $authModuleIds);
    
                // Pastikan module_name dan url di-set dengan benar
                $module->module_parent = $module->module_parent ?? ""; // Default to empty string if null
                $module->url = $module->url ?? ""; // Default to empty string if null
    
                // Dapatkan children secara rekursif
                $children = $this->_buildTree_($modules, $role_groups, $module->module_id);
                $module->children = $children ?: []; // Assign children jika ada atau array kosong
                $module->have_children = !empty($children); // Menentukan apakah ada children
                $branch[] = $module; // Tambahkan modul ke branch
            }
        }
    
        return $branch; // Kembalikan branch yang sudah dibangun
    }
    
    
}