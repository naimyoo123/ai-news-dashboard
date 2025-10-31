<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class NewsArticle extends Model {
    use HasFactory;
    protected $fillable = ['country_code','source','author','title','description','content','url','url_to_image','published_at','category'];
    protected $casts = ['published_at' => 'datetime'];
}
