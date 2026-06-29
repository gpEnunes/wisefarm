<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Plantation model representing a crop planting cycle in a field.
 *
 * @property int         $id
 * @property int         $field_id
 * @property int         $crop_id
 * @property \Carbon\Carbon      $planted_at
 * @property \Carbon\Carbon|null $expected_harvest_at
 * @property \Carbon\Carbon|null $harvested_at
 * @property float|null  $area_ha
 * @property float|null  $yield_kg
 * @property string|null $notes
 */
class Plantation extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'crop_id',
        'planted_at',
        'expected_harvest_at',
        'harvested_at',
        'area_ha',
        'yield_kg',
        'notes',
    ];

    protected $casts = [
        'planted_at'          => 'date',
        'expected_harvest_at' => 'date',
        'harvested_at'        => 'date',
    ];

    /**
     * The field where this plantation is growing.
     *
     * @return BelongsTo<Field, Plantation>
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * The crop type for this plantation.
     *
     * @return BelongsTo<Crop, Plantation>
     */
    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }
}
