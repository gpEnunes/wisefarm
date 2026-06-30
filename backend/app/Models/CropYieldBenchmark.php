<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropYieldBenchmark extends Model
{
    protected $fillable = ['crop_id', 'country_code', 'year', 'yield_kg_ha'];

    protected $casts = [
        'yield_kg_ha' => 'float',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
