<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address','opening_hours','latitude','longitude','gerant_id','statut','contact'];

    public function gerant()
    {
        return $this->belongsTo(User::class, 'gerant_id');
    }

    public function superviseur()
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

}
