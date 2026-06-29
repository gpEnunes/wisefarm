<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Farm model representing a farm owned by a user.
 *
 * @property int         $id
 * @property int         $user_id
 * @property string      $name
 * @property string|null $location
 * @property float|null  $total_area_ha
 */
class Farm extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'location', 'total_area_ha'];

    /**
     * The user who owns this farm.
     *
     * @return BelongsTo<User, Farm>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The fields that belong to this farm.
     *
     * @return HasMany<Field>
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}
