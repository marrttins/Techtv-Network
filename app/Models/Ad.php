<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image_path',
        'link',
        'page',
        'location',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    /**
     * Get active ad for a specific slot location and page.
     *
     * @param string $location
     * @param string|null $page
     * @return \App\Models\Ad|null
     */
    public static function getSlot(string $location, ?string $page = null)
    {
        $query = static::where('is_active', true);

        if ($page) {
            $query->where(function($q) use ($location, $page) {
                $q->where(function($sub) use ($location, $page) {
                    $sub->where('location', $location)
                        ->where('page', $page);
                })->orWhere(function($sub) use ($location) {
                    $sub->where('location', $location)
                        ->where('page', 'global');
                });
            });
        } else {
            $query->where('location', $location);
        }

        return $query->latest()->first();
    }
}
