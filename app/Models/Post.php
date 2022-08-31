<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'slug','imagen','descripcion'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            $post->slug = $post->createSlug($post->titulo);

            $post->save();
        });

        
    }

    /** 
     * create slug
     *
     * @return response()
     */
    private function createSlug($titulo)
    {
        if (static::whereSlug($slug = Str::slug($titulo))->exists()) {
            $max = static::whereTitulo($titulo)->latest('id')->skip(1)->value('slug');
            if (is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }

            return "{$slug}-2";
        }

        return $slug;
    }

}
