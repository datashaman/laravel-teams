<?php

namespace Datashaman\Teams\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
