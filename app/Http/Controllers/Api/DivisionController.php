<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Divisi;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->get('name');

        $query = Divisi::select('uuid', 'name');

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $divisions = $query->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Divisions retrieved successfully',
            'data' => [
                'divisions' => $divisions->items(),
            ],
            'pagination' => [
                'current_page' => $divisions->currentPage(),
                'total_page' => $divisions->lastPage(),
                'total_count' => $divisions->total(),
                'per_page' => $divisions->perPage(),
            ],
        ]);
    }
}
