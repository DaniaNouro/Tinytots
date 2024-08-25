<?php
namespace App\Interfaces;

interface UserSortRepositoryInterface{
    
public function sortAcs($attribute,$model);
public function sortDesc($attribute,$model);

}
