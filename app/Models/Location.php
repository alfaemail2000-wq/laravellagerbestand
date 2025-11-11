<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 *
 * Represents a physical warehouse location. Each location can have incoming
 * and outgoing movements.
 */
class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all movements where this location is the destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomingMovements()
    {
        return $this->hasMany(Movement::class, 'to_location_id');
    }

    /**
     * Get all movements where this location is the source.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outgoingMovements()
    {
        return $this->hasMany(Movement::class, 'from_location_id');
    }
}