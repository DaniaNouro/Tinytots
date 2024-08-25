<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responces\Response;
use App\Interfaces\UserSearchRepositoryInterface;
use App\Interfaces\UserSortRepositoryInterface;

class ParentSearchSortController extends Controller
{
    protected $UserSearchRepository;
    protected $UserSortRepository;

    public function __construct(
        UserSearchRepositoryInterface $UserSearchRepository,
        UserSortRepositoryInterface $UserSortRepository
    ) {
        $this->UserSearchRepository = $UserSearchRepository;
        $this->UserSortRepository = $UserSortRepository;
    }
    /*_________________________________________________________________________________________________________________*/
    public function searchByName($name)
    {
        $users = $this->UserSearchRepository->searchByName($name, 'Parentt');
        if ($users->isEmpty()) {
            return Response::Success([], 'There are no users with the provided name.', 200);
        }
        return Response::Success($users, 'Success', 200);
    }
    /*_________________________________________________________________________________________________________________*/
    public function searchByEmail($email)
    {
        $users = $this->UserSearchRepository->searchByEmail($email, 'User');
        //بدي حط شرط تأكد انو ال ه\ول آباء 
       // if($users)
        if ($users->isEmpty()) {
            return Response::Success([], 'There are no users with the provided email.', 200);
        }
        return Response::Success($users, 'Success', 200);
    }

    /*_________________________________________________________________________________________________________________*/
    public function searchByPhone($phone)
    {
        $data = $this->UserSearchRepository->searchByPhone($phone, 'Parentt');
        if ($data['message_validation']) {
            return Response::Validation([], 'Enter a vlaid phone.');
        } elseif (collect($data['users'])->isEmpty()) {
            return Response::Success($data['users'], 'There are no users with the provided phone.', 200);
        } else {
            return Response::Success($data['user'], 'success', 200);
        }
    }
    /*_________________________________________________________________________________________________________________*/
    public function sortAcs($id)
    {
        $data = $this->UserSortRepository->sortAcs($id, 'Parentt');
        return Response::Success($data, 'success', 200);
    }
    /*_________________________________________________________________________________________________________________*/
    public function sortDesc($id)
    {
        $data = $this->UserSortRepository->sortDesc($id, 'Parentt');
        return Response::Success($data, 'success', 200);
    }
}
