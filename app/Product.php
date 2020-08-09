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

    public function user() {
        return $this->belongsTo(User::class);
    }

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
