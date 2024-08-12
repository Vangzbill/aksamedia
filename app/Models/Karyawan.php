<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawan';

    public function division()
    {
        return $this->belongsTo(Divisi::class, 'division_id', 'id');
    }

    public static function getData()
    {
        return self::with(['division:id,uuid,name'])
                    ->select('uuid', 'image', 'name', 'phone', 'division_id', 'position');
    }

    public static function createKaryawan($request)
    {
        DB::beginTransaction();
        try {
            $karyawan = new Karyawan();
            $karyawan->uuid = Str::uuid();
            $karyawan->name = $request->name;
            $karyawan->phone = $request->phone;
            $karyawan->division_id = $request->division_id;
            $karyawan->position = $request->position;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $karyawan->uuid . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/employees', $filename);
                $karyawan->image = $filename;
            }

            $karyawan->save();
            DB::commit();
            return $karyawan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
