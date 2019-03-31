<?php

namespace Datashaman\Teams\Models;

use Datashaman\Teams\Contracts\ProjectInterface;
use Datashaman\Teams\Contracts\TeamInterface;
use DB;
use Illuminate\Database\Eloquent\Model;

class Project extends Model implements ProjectInterface
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(TeamInterface::class);
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
}
