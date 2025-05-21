<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'Admin';
    case TRAINER = 'Trainer';
    case STUDENT = 'Student';
    case EVALUATOR = 'Evaluator';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::TRAINER => 'Trainer',
            self::STUDENT => 'Student',
            self::EVALUATOR => 'Evaluator',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-red-100 text-red-800',
            self::TRAINER => 'bg-blue-100 text-blue-800',
            self::STUDENT => 'bg-green-100 text-green-800',
            self::EVALUATOR => 'bg-purple-100 text-purple-800',
        };
    }
}
