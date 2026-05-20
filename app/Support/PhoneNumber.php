<?php

namespace App\Support;

class PhoneNumber
{
    /**
     * @return list<string>
     */
    public static function rules(bool $required = true): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'max:30',
            'regex:/^\+?[0-9][0-9\s().-]{6,29}$/',
        ];
    }

    public static function normalize(string $phone): string
    {
        return preg_replace('/(?!^\+)[^\d]/', '', trim($phone));
    }
}
