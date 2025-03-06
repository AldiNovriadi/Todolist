<?php

namespace App\Models;

use App\Models\NoteListDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteList extends Model
{
    use HasFactory;

    protected $table = 'note_lists';

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function details()
    {
        return $this->hasMany(NoteListDetail::class, 'note_list_id');
    }
}
