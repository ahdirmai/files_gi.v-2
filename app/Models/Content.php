<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Content extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;
    protected $fillable = ['name', 'isPrivate', 'url'];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function contentable()
    {
        return $this->morphTo();
    }

    public function baseFolder()
    {
        return $this->belongsTo(BaseFolder::class, 'basefolder_id', 'id');
    }

    public function contents()
    {
        return $this->morphMany('App\Models\Content', 'contentable');
    }

    public function access()
    {
        return $this->hasMany(ContentAccess::class, 'content_id', 'id');
    }

    public function deleteWithInnerFolder()
    {
        if (count($this->contents) > 0) {
            foreach ($this->contents as $content) {
                if ($content->type == 'file') {
                    $content->getMedia('file')->first()->forceDelete();
                }
                $content->deleteWithInnerFolder();
            }
        }
        $this->delete();
    }

    public function getFolder()
    {
        return $this->contents->where('type', 'folder');
    }
}
