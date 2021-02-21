<?php

namespace App;

use App\Enumerations\NameStandard as Names;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Avatar;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    // Line 38 and 48 is getting for fake avatar.
    
    /**
     * Retrieve the Avatar instance.
     * 
     * @param  array|null $config
     * @return \Laravolt\Avatar\Avatar
     */
    protected function laravolt($config = null)
    {
    	return new Avatar($config ?? config('avatar'));
    }

    /**
     * Retrieve the url of the user's avatar.
     * 
     * @return string
     */
    public function getAvatarAttribute()
    {
    	return $this->photo ?? $this->laravolt()
    		->create($this->fullname)
    		->toBase64()
    		->getEncoded();
    }

    /**
     * Retrieve the global display name.
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
    	return strtr(Names::FULLNAME, [
    		Names::FIRSTNAME => $this->firstname,
    		Names::MIDDLEINITIAL => str_limit($this->middlename, 1, '.'),
    		Names::LASTNAME => $this->lastname,
    	]);
    }
}
