<?php

namespace App\Enums;

enum CertificateStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::EXPIRED => 'Expired',
            self::REVOKED => 'Revoked',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'bg-green-100 text-green-800',
            self::EXPIRED => 'bg-yellow-100 text-yellow-800',
            self::REVOKED => 'bg-red-100 text-red-800',
        };
    }
}
