<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    /**
     * @return array
     */
    public static function validatorRules () {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ];
    }
}
