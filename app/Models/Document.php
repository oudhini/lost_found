<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = ['type_document', 'nom_present_sur_le_document', 'status','lieu_de_perte','user_id','photos','additional_info','numero_du_document','depot_id','contact_info','date_de_perte'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

}
