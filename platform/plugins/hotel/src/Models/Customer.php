<?php

namespace Botble\Hotel\Models;

use Botble\Base\Models\BaseModel;

class Customer extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ht_customers';

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'phone',
        'dob',
    ];

    /**
     * Always capitalize the first name when we retrieve it
     * @param string $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Always capitalize the last name when we retrieve it
     * @param string $value
     * @return string
     */
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
