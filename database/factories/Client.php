<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // client belongs to many platforms, therefore, platform belongs to many clients
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'client_has_platforms', 'client_id', 'platform_id');
    }

    // client has may sites, therefore, a site belongs to a client
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function contractStatus()
    {
        return $this->belongsTo(ContractStatus::class);
    }
}
