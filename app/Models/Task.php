<?php

namespace App\Models;

use App\Filters\QuerySortAndFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'priority',
        'title',
        'description',
        'created_at',
        'completed_at',
        'task_id'
    ];
    public $timestamps = false;

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->with('tasks');
    }

    public function scopeFilter(Builder $builder, QuerySortAndFilter $filter)
    {
        $filter->apply($builder);
    }
}
