<?php

namespace App\Enums;

enum Platform: string
{
    case Facebook = 'Facebook';
    case Instagram = 'Instagram';
    case LinkedIn = 'LinkedIn';
    case YouTube = 'YouTube';
    case TikTok = 'TikTok';
    case X = 'X (Twitter)';
    case Pinterest = 'Pinterest';
    case Snapchat = 'Snapchat';

    public static function options(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
