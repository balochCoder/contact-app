<?php

namespace App\Models;

use App\Scopes\CompanySearchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'email', 'website','user_id'];


    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function userCompanies()
    {
        return self::where('user_id',Auth::id())->orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
    }

    public static function booted()
    {
        static::addGlobalScope(new CompanySearchScope);      
        
    }
    
}
