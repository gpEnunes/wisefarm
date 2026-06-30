<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropTip extends Model
{
    protected $fillable = ['crop_id', 'type', 'soil_type', 'body'];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
