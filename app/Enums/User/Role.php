<?php

namespace App\Enums\User;

enum Role: string
{
    const ADMIN = 'admin';
    const USER = 'user';
    const DOCTOR = 'doctor';
    const PATIENT = 'patient';
    const RECEPTIONIST='receptionist';
}
