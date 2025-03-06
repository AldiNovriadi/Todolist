<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteListDetailItem extends Model
{
    use HasFactory;

    protected $table = 'note_list_detail_items';

    protected $fillable = [
        'note_list_detail_id',
        'name',
        'status',
    ];
}
