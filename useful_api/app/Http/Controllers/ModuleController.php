<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModule; 
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
   
    public function index(): JsonResponse
    {
        $Modules = Module::with(['user', 'module'])->get(); 
        return response()->json($modules, 200);
    }

    public function activate(Module $module)
    {
        $module->active = true;
        $module->save();

        return response()->json([
            'message' => "Module $id activé avec succès.",
            'status' => 'success'
        ], 200);
    }

    public function deactivate(Module $module)
    {
        $module->active = false;
        $module->save();

        return response()->json([
            'message' => "Module $id désactivé avec succès.",
            'status' => 'success'
        ], 200);
    }
}
