<?php

namespace App\Enum;

enum OrderStatus: string
{
    case Pending = 'pending';
    case InDelivery = 'in_delivery';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
