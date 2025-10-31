<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code','summary','category_trends','analysis','generated_at'
    ];

    protected $casts = [
        'category_trends' => 'array',
        'generated_at' => 'datetime'
    ];
}
