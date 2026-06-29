<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Field model representing a section of land within a farm.
 *
 * @property int         $id
 * @property int         $farm_id
 * @property string      $name
 * @property float|null  $area_ha
 * @property string|null $soil_type
 * @property bool        $irrigated
 */
class Field extends Model
{
    use HasFactory;

    // farm_id is intentionally excluded — always set via relationship scope
    protected $fillable = ['name', 'area_ha', 'soil_type', 'irrigated'];

    protected $casts = ['irrigated' => 'boolean'];

    /**
     * The farm this field belongs to.
     *
     * @return BelongsTo<Farm, Field>
     */
    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    /**
     * The plantations active in this field.
     *
     * @return HasMany<Plantation>
     */
    public function plantations(): HasMany
    {
        return $this->hasMany(Plantation::class);
    }
}
