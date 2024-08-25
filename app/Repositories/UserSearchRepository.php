<?php

namespace App\Repositories;

use App\Interfaces\UserSearchRepositoryInterface;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Parentt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;

class UserSearchRepository implements UserSearchRepositoryInterface
{
    public function searchByName($name, $modelName)
    {
        $modelClass = "App\\Models\\" . $modelName;

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class '$modelName' not found.");
        }

        $model = new $modelClass;

        if ($model instanceof Parentt) {
            $users = $model::where('father_first_name', 'LIKE', "%{$name}%")
                ->orWhere('father_last_name', 'LIKE', "%{$name}%")
                ->orWhere('mother_first_name', 'LIKE', "%{$name}%")
                ->orWhere('mother_last_name', 'LIKE', "%{$name}%")
                ->get();
        } else {
            $users = $model::where('first_name', 'LIKE', "%{$name}%")
                ->orWhere('last_name', 'LIKE', "%{$name}%")
                ->get();
        }

        return $users;
    }

    public function searchByEmail($email, $modelName)
    {
        $modelClass = "App\\Models\\" . $modelName;

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class '$modelName' not found.");
        }

        $model = new $modelClass;

        $validator = Validator::make(['email' => $email], ['email' => 'required|email'], ['email.required' => 'Please enter Email', 'email.email' => 'Please enter a valid Email']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $users = $model::where('email', 'LIKE', "%{$email}%")->get();
        return $users;
    }

    public function searchByPhone($phone, $modelName)
    {
        $data = [];
        $modelClass = "App\\Models\\" . $modelName;

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class '$modelName' not found.");
        }

        $model = new $modelClass;

        $validator = Validator::make(['phone' => $phone], [
            'phone' => 'required|regex:/^[0-9]{10}$/'
        ], [
            'phone.required' => 'Please enter a phone number to search.',
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        if ($validator->fails()) {
            $data['message_validation'] = ['errors' => $validator->errors()];
            return $data;
        }

        $data['message_validation'] = '';

        if ($model instanceof Parentt) {
            $data['users'] = $model::where('father_phone_number', 'LIKE', "%{$phone}%")
                ->orWhere('mother_phone_number', 'LIKE', "%{$phone}%")-> get();

                
        } else {
            $data['users'] = $model::where('phone_number', 'LIKE', "%{$phone}%")->get();
        }

        $data['user_id'] = $data['users']->pluck('user_id');
        $data['user'] = User::find($data['user_id'])->first();

        return $data;
    }
}
