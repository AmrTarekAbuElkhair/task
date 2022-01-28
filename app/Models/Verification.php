<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;
    protected $table='verifications';
    protected $fillable=['user_id','verifications_code'];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
