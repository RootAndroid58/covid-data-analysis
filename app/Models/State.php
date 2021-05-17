<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CacheClear;
use App\Models\Country;

class State extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Auditable;
    use CacheClear;

    public $table = 'states';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'country_code',
        'state_code',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function cities()
    {
        return $this->belongsToMany(City::class);
    }

    public function country()
    {
        return $this->belongsToMany(Country::class)->withPivot('country_id','state_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
