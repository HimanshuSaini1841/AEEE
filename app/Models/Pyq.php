<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Pyq extends Model
{
    use HasFactory;

    protected $table = 'pyqs';

    protected $fillable = [
        'title',
        'year',
        'subject',
        'file_path',
        'file_name',
        'file_size_formatted',
        'file_size_bytes',
        'total_pages',
        'is_active',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_active' => 'boolean',
            'view_count' => 'integer',
            'total_pages' => 'integer',
            'file_size_bytes' => 'integer',
        ];
    }

    /**
     * Get public download/stream URL for the PDF.
     */
    public function getDownloadUrlAttribute(): string
    {
        return url('/api/v1/pyqs/' . $this->id . '/download');
    }

    /**
     * High-speed scopes for student catalog.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }
}
