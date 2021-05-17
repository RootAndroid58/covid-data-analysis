<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CacheClear;

class City extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Auditable;
    use CacheClear;

    public $table = 'cities';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'population',
        'state_code',
        'country_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function cityResources()
    {
        return $this->hasMany(Resource::class, 'city_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
