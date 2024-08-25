<?php
namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait EmailSenderTrait{



 public function sendEmail($to,$data,$view=null)
{
  
  Mail::To($to)->send($data);

}




}

