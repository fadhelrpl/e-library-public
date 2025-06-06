<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $with = ['author', 'category'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('body', 'like', '%' . $search . '%');
        });

        $query->when($filters['category'] ?? false, function ($query, $category) {
            return $query->whereHas('category', fn ($query) =>
                $query->where('slug', $category)
            );
        });

        $query->when($filters['author'] ?? false, function ($query, $author) {
            return $query->whereHas('author', fn ($query) =>
                $query->where('slug', $author)
            );
        });
    }

    public function author() {
        return $this->belongsTo(Author::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
