<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'scientific_name', 'category', 'avg_growth_days', 'icon'];

    public function profile()
    {
        return $this->hasOne(CropProfile::class);
    }

    public function soilSuitabilities()
    {
        return $this->hasMany(CropSoilSuitability::class);
    }

    public function tips()
    {
        return $this->hasMany(CropTip::class);
    }

    public function yieldBenchmarks()
    {
        return $this->hasMany(CropYieldBenchmark::class)->orderBy('year');
    }
}
