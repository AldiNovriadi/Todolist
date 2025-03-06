<?php

namespace App\Models;

use App\Models\NoteList;
use App\Models\NoteListDetailItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteListDetail extends Model
{
    use HasFactory;

    protected $table = 'note_list_details';

    protected $fillable = [
        'note_list_id',
        'name',
        'status',
    ];

    public function master()
    {
        return $this->belongsTo(NoteList::class, 'note_list_id');
    }

    public function items()
    {
        return $this->hasMany(NoteListDetailItem::class, 'note_list_detail_id');
    }

}
