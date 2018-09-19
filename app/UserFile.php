<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    protected $table = 'user_files';

    protected $fillable = [
        'description', 'email', 'hash_user', 'hash_file', 'file_name', 'file_presence', 'number_parts'
    ];

}
