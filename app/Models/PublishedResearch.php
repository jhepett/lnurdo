<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishedResearch extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsTo(User::class,  'owner_id','id',);
    }
}
