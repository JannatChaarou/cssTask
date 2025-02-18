<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Allow mass assignment for the 'title', 'description', 'category_id', 'priority', 'completed', and 'user_id' fields
    protected $fillable = [
        'title', 'description', 'category_id', 'priority', 'completed', 'user_id'  // Add user_id to the fillable array
    ];

    /**
     * A task belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A task belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
