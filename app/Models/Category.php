<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Allow mass assignment for the 'name' and 'user_id' fields
    protected $fillable = ['name', 'user_id'];  // Add user_id to the fillable array

    /**
     * A category belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A category has many tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
