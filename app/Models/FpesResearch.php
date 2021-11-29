<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpesResearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'involvement_id',
        'description',
        'from',
        'to',
        'status',
        'owner_id'
    ];

    public function involvement()
    {
        return $this->belongsTo(Involvement::class);
    }
    public function evaluationPeriod()
    {
        return $this->belongsTo(EvaluationPeriod::class, 'evaluation_period_id');
    }
    public function users(){
        return $this->belongsTo(User::class,  'owner_id','id',);
    }
}
