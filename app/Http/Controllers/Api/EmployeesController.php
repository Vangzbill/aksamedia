<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->get('name');
        $division = $request->get('division');

        $query = Karyawan::getData();

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($division) {
            $query->whereHas('division', function($q) use ($division) {
                $q->where('id', $division);
            });
        }

        $employees = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved successfully',
            'data' => [
                'employees' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->uuid,
                        'image' => $employee->image,
                        'name' => $employee->name,
                        'phone' => $employee->phone,
                        'division' => [
                            'id' => $employee->division->uuid,
                            'name' => $employee->division->name,
                        ],
                        'position' => $employee->position,
                    ];
                }),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'total_page' => $employees->lastPage(),
                'total_count' => $employees->total(),
                'per_page' => $employees->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'division_id' => 'required|exists:divisi,id',
            'position' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $employee = Karyawan::createKaryawan($request);
        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee failed to create',
            ], 500);
        }
    }

    public function update($uuid, Request $request)
    {
        $employee = Karyawan::where('uuid', $uuid)->first();
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found',
            ], 404);
        }

        DB::beginTransaction();
        try {
            if ($request->has('name')) {
                $employee->name = $request->name;
            }

            if ($request->has('phone')) {
                $employee->phone = $request->phone;
            }

            if ($request->has('division_id')) {
                $employee->division_id = $request->division_id;
            }

            if ($request->has('position')) {
                $employee->position = $request->position;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $employee->uuid . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/employees', $filename);
                $employee->image = $filename;
            }

            $employee->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        $employee = Karyawan::where('uuid', $uuid)->first();
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found',
            ], 404);
        }

        DB::beginTransaction();
        try {
            $employee->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
