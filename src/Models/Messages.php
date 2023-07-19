<?php

namespace Omnia\Oalivechat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;
    
    protected $fillable = ['msg','sender','receiver','attachment','is_seen'];
}
