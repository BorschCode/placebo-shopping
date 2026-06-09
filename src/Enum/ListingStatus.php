<?php

namespace App\Enum;

enum ListingStatus: string
{
    case Active = 'active';
    case Sold = 'sold';
    case Archived = 'archived';
}
