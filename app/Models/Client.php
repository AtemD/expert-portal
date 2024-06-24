<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'contract_status_id',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // client has many contacts, therefore, a contact belongs to a client
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    // client belongs to many platforms, therefore, platform belongs to many clients
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'client_has_platforms', 'client_id', 'platform_id');
    }

    // client has may sites, therefore, a site belongs to a client
    public function sites(): HasMany   
    {
        return $this->hasMany(Site::class);
    }

    public function contractStatus(): BelongsTo
    {
        return $this->belongsTo(ContractStatus::class);
    }
}
