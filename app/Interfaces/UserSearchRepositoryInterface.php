<?php
namespace App\Interfaces;

interface UserSearchRepositoryInterface{
    
public function searchByName($name,$model);
public function searchByEmail($name,$model);
public function searchByPhone($phone,$model);

}
