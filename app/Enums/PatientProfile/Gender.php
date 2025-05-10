<?php

namespace App\Enums\PatientProfile;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            Gender::Male => 'Male',
            Gender::Female => 'Female',
            Gender::Other => 'Other'
        };
    }
}
