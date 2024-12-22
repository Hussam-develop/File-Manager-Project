<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackUpFile extends Model
{
    use HasFactory;
    public $table='backup_files';
    protected $fillable = [
        'backup_file',
        'file_id',
        'backup_path'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
