<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Crop model representing a type of crop (reference data).
 *
 * @property int         $id
 * @property string      $name
 * @property string|null $scientific_name
 * @property string      $category
 * @property int|null    $avg_growth_days
 * @property string      $icon
 */
class Crop extends Model
{
    protected $fillable = ['name', 'scientific_name', 'category', 'avg_growth_days', 'icon'];
}
