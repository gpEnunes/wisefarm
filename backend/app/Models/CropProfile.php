<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropProfile extends Model
{
    protected $fillable = [
        'crop_id',
        'faostat_item_code',
        'description',
        'optimal_temp_min',
        'optimal_temp_max',
        'ph_min',
        'ph_max',
        'annual_rainfall_min',
        'annual_rainfall_max',
        'drought_tolerance',
        'frost_tolerance',
        'faostat_imported_at',
    ];

    protected $casts = [
        'optimal_temp_min'    => 'float',
        'optimal_temp_max'    => 'float',
        'ph_min'              => 'float',
        'ph_max'              => 'float',
        'faostat_imported_at' => 'datetime',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
