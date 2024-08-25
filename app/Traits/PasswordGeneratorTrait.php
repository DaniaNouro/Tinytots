<?php
namespace App\Traits;


trait PasswordGeneratorTrait{

    public function generateRandomPassword($length=8){
      $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';

      $randomString = '';

      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }

      return $randomString;

    }
  
}