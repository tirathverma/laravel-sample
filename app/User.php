<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'address', 'username', 'employee_access', 'password', 'api_token', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
    public static $rules = array(
        'name' => 'required',
        'phone' => 'numeric',
        'username' => 'required||unique:users',
        'password' => 'required',
        'password_confirmation' => 'required|same:password',
    );

    public function role() {
        return $this->belongsTo('App\Role');
    }

}
