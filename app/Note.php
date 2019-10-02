<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    protected $fillable = ['url', 'status', 'title', 'grade', 'subject', 'scraped_text', 'summarized_text', 'added_by'];
}
