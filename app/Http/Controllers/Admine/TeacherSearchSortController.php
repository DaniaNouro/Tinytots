<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Http\Responces\Response;
use App\Interfaces\UserSearchRepositoryInterface;
use App\Interfaces\UserSortRepositoryInterface;
use App\Models\Teacher;
use App\Repositories\UserSearchRepository;
use Database\Seeders\TeacherSeeder;
use Illuminate\Http\Request;

class TeacherSearchSortController extends Controller
{
   
       protected $UserSearchRepository;
       protected $UserSortRepository;

       public function __construct(
        UserSearchRepositoryInterface $UserSearchRepository,
        UserSortRepositoryInterface $UserSortRepository)
       {
           $this->UserSearchRepository = $UserSearchRepository;
           $this->UserSortRepository=$UserSortRepository;
       }
/*_________________________________________________________________________________________________________________*/
            public function searchByName($name){
            $users=$this->UserSearchRepository->searchByName($name,'Teacher');
            if ($users->isEmpty()) {
                return Response::Success([], 'There are no users with the provided name.',200);
            }
              return Response::Success($users, 'Success', 200);
            }
/*_________________________________________________________________________________________________________________*/
        public function searchByEmail($email){
        $users=$this->UserSearchRepository->searchByEmail($email,'User');
            if ($users->isEmpty()) {
                return Response::Success([], 'There are no users with the provided email.',200);
            }
            return Response::Success($users, 'Success', 200);
        }
   
/*_________________________________________________________________________________________________________________*/
        public function searchByPhone($phone){
            $data=$this->UserSearchRepository->searchByPhone($phone,'Teacher');
            if ($data['message_validation']) {
                return Response::Validation([], 'Enter a vlaid phone.');
            }
            elseif(collect($data['users'])->isEmpty()){
            return Response::Success($data['users'], 'There are no users with the provided phone.', 200);
            }else{
                return Response::Success($data['user'], 'success', 200);
            }
        }
/*_________________________________________________________________________________________________________________*/
        public function sortAcs($id){
           $data=$this->UserSortRepository->sortAcs($id,'Teacher');
           return Response::Success($data, 'success', 200);
        }
/*_________________________________________________________________________________________________________________*/
        public function sortDesc($id){
            $data=$this->UserSortRepository->sortDesc($id,'Teacher');
            return Response::Success($data, 'success', 200);
         }
}
