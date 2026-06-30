<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropSoilSuitability extends Model
{
    protected $fillable = ['crop_id', 'soil_type', 'suitability'];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
