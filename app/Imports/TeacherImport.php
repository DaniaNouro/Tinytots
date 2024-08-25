<?php
namespace App\Imports;

use App\Http\Requests\TeacherSignupRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

 
class TeacherImport implements ToModel ,WithHeadingRow
{
    public function model(array $row)
    {
       $validator = Validator::make($row, (new TeacherSignupRequest())->rules());
        if($validator->fails()){
            Log::error('Validation failed for row: '.json_encode($row) .' with errors: '.json_encode($validator->errors()->all()));
            return null; // Ignore this row
        }
       
        $user = User::query()->create([
            'email' => $row['email'],
            'password' => Hash::make($row['national_id'])
          ]);

            $teacherRole = Role::where('name', 'teacher')->first();
            $user->assignRole($teacherRole);

            $permissions = $teacherRole->permissions()->pluck('name')->toArray();
            $user->givePermissionTo($permissions);

            return new Teacher([
                'user_id'=>$user->id,
                'first_name'=>$row['first_name'],
                'last_name'=>$row['last_name'],
                'gender'=>$row['gender'],
                'date_of_birth'=>$row['date_of_birth'],
                'phone_number'=>$row['phone_number'],
                'address'=>$row['address'],
                'details'=>$row['details'],
                'national_id'=>$row['national_id'],
            ]);
       
    }
}
  