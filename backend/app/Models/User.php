<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_image',
        'full_name',
        'age',
        'street',
        'neighborhood',
        'state',
        'biography',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['full_address'];

    /**
     * Get the user's full address.
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, {$this->neighborhood}, {$this->state}";
    }
}
