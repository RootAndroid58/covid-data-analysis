<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\MultiTenantModelTrait;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NewReq extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;
    use MultiTenantModelTrait;

    public const MODEL_SELECT = [
        'Resource'     => 'Add New Resource',
        'Category'     => 'Add New Category',
        'Sub-Category' => 'Add New Sub-Category',
        'Country'      => 'Add New Country',
        'State'        => 'Add New State',
        'City'         => 'Add New City',
        'Scrape'       => 'Add New Website for auto scraping',
        'Contact'      => 'Contact Us',
        'Feedback'     => 'Give us a Feedback',
    ];

    public $table = 'new_reqs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'model',
        'email_id',
        'data',
        'message',
        'status',
        'admin_message',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function email()
    {
        return $this->belongsTo(User::class, 'email_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
