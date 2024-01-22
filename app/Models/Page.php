<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Page extends Model
{
    use HasFactory;
    public $table = 'page_sections';

    protected $fillable = ['content', 'user_id', 'title'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
