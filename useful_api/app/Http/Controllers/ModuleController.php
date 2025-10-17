<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(): JsonResponse
    {
        $modules = Module::all(['id', 'name', 'description']);

        return response()->json($modules, 200);
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $user = $request->user();

        // Vérifier si la relation existe déjà
        $existingRelation = $user->modules()->where('module_id', $id)->first();

        if ($existingRelation) {
            // Mettre à jour l'état
            $user->modules()->updateExistingPivot($id, ['active' => true]);
        } else {
            // Créer la relation
            $user->modules()->attach($id, ['active' => true]);
        }

        return response()->json(['message' => 'Module activated'], 200);
    }

    public function deactivate(Request $request, int $id): JsonResponse
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $user = $request->user();

        // Vérifier si la relation existe
        $existingRelation = $user->modules()->where('module_id', $id)->first();

        if ($existingRelation) {
            // Mettre à jour l'état
            $user->modules()->updateExistingPivot($id, ['active' => false]);
        } else {
            
            $user->modules()->attach($id, ['active' => false]);
        }

        return response()->json(['message' => 'Module deactivated'], 200);
    }
}