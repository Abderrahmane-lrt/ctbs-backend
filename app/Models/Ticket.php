<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = ['showtime_id', 'user_id', 'ticket_code', 'is_used', 'validated_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function showtime(){
        return $this->belongsTo(Showtime::class, 'showtime_id');
    }
}
