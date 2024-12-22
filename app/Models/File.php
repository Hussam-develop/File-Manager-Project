<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'user_id',
        'group_id',
        'file',
        'checkStatus'
        // 'modify'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class,'file_id');
    }

    public function backupFiles()
    {
        return $this->hasMany(BackUpFile::class,'file_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
