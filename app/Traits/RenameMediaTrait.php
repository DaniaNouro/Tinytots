<?php
namespace App\Traits;

trait RenameMediaTrait{

    public function generateFilename($image)
    {
        return uniqid() . '.' . $image->getClientOriginalExtension();
    }
    
}