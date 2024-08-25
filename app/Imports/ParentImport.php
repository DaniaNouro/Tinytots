<?php
namespace App\Imports;

use App\Http\Requests\ParentSignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Parentt;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
 
class ParentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
           
        $validator = Validator::make($row, (new ParentSignupRequest())->rules());
        if($validator->fails()){
            Log::error('Validation failed for row: ' . json_encode($row) . ' with errors: '.json_encode($validator->errors()->all()));
            return null; // Ignore this row
        }

        
        else{
            $user = User::query()->create([
                'email' => $row['email'],
                'password' => Hash::make($row['national_id'])
              ]);
        $parentRole =Role::query()->where('name', 'parent')->first();
        $user->assignRole($parentRole);
  
        $permissions = $parentRole->permissions()->pluck('name')->toArray();
        $user->givePermissionTo($permissions);

        return new Parentt([
            'user_id'=>$user->id,
            'father_first_name'=>$row['father_first_name'],
            'father_last_name'=>$row['father_last_name'],
            'father_phone_number'=>$row[ 'father_phone_number'],
            'mother_first_name'=>$row[ 'mother_first_name'],
            'mother_last_name'=>$row['mother_last_name'],
            'mother_phone_number'=>$row[  'mother_phone_number'],
            'national_id'=>$row[ 'national_id'],
        ]);
    }
}
} 

