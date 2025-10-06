<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $casts = [
        'id' => 'integer',
        'question' => 'string',
        'answer' => 'string',
        'lang' => 'string',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function scopeStatus($query)
    {
        return $query->where('status', true);
    }
    public function scopeLang($query)
    {
        return $query->where('lang', $this->getLocale());
    }
}
