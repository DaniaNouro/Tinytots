<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Traits\PasswordGeneratorTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Http\Requests\StudentSignupRequest;
use App\Http\Responces\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class StudentImport implements ToModel, WithHeadingRow
{
    use PasswordGeneratorTrait;
    public function model(array $row)
    {
        $validator = Validator::make($row, (new StudentSignupRequest())->rules());
        if ($validator->fails()) {
 
                Log::error('Validation failed for row: ' . json_encode($row) . ' with errors: ' . json_encode($validator->errors()->all()));
                return null; // Ignore this row
        } 
            $parentUser = User::where('email', $row['email'])->first();

            if ($parentUser) {
                $parent = $parentUser->parent;

                if ($parent) {
                    $tempCode = $this->generateRandomPassword(4);
                    $user = User::create([
                        'email' => $row['email'],
                        'password' => Hash::make($tempCode)
                    ]);

                    $studentRole = Role::where('name', 'student')->first();
                    $user->assignRole($studentRole);

                    $permissions = $studentRole->permissions()->pluck('name')->toArray();
                    $user->givePermissionTo($permissions);

                    return new Student([
                        'user_id' => $user->id,
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'gender' => $row['gender'],
                        'date_of_birth' => $row['date_of_birth'],
                        'address' => $row['address'],
                        'deatails' => $row['deatails'],
                        'level' => $row['level'],
                        'parentt_id' => $parent->id
                    ]);
                } else {
                    Log::error('Parent not found for email: ' . $row['email']);
                return null; // Ignore this row
                }
            } else {
                Log::error('User not found for email: ' . $row['email']);
                return null; // Ignore this row
            }
        }
    }

