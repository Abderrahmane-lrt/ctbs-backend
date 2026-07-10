<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    //
    protected $fillable = ['movie_id', 'room_name', 'capacity','start_time', 'price'];

    public function movie(){
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
