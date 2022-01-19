<?php

namespace App\Models;

use App\Scopes\ContactFilterScope;
use App\Scopes\ContactSearchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'address','company_id','user_id'];

   
    public function company()
    {
        return $this->belongsTo(Company::class)->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('id', 'desc');
    }

    

    public static function booted()
    {
        static::addGlobalScope(new ContactSearchScope);
        static::addGlobalScope(new ContactFilterScope);        
        
    }
    
}
