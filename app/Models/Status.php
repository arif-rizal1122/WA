<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

   protected $table = 'statuses';

   protected $fillable = [
    'picture',
    'caption'
   ];



   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_id', 'id');
   }


   public function comments(): HasMany
   {
      return $this->hasMany(Comment::class, 'status_id', 'id');
   }



}
