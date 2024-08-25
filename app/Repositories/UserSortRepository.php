<?php

namespace App\Repositories;

use App\Http\Responces\Response;
use App\Interfaces\UserSortRepositoryInterface;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;


class UserSortRepository implements UserSortRepositoryInterface
{
public function sortAcs($attribute, $modelName)
{
        $modelClass =  "App\\Models\\" . $modelName; 
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class '$modelName' not found.");
        }
        $model = new $modelClass;switch ($attribute) {
            case '1':
            return $users=$model::OrderBy('first_name','asc')->get();
            case '2':
           return $users=$model::OrderBy('last_name','asc')->get();
            case '3':
         return $users=$model::OrderBy('national_id','desc')->get();
            case '4':
                return 'profile/parents';
            default:
                return null;
        }
    }    
/*_________________________________________________________________________________________________________________*/

public function sortDesc($attribute, $modelName)
{
    $modelClass =  "App\\Models\\" . $modelName; 
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class '$modelName' not found.");
        }
        $model = new $modelClass;switch ($attribute) {
            case '1':
            return $users=$model::OrderBy('first_name','desc')->get();
            case '2':
           return $users=$model::OrderBy('last_name','desc')->get();
            case '3':
          return $users=$model::OrderBy('national_id','desc')->get();
            case '4':
                return 'profile/parents';
            default:
                return null;
        }
}
/*_________________________________________________________________________________________________________________*/
}