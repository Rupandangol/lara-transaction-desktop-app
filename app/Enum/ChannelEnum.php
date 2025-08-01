<?php

namespace App\Enum;

enum ChannelEnum: string
{
    case CASH = 'CASH';
    case THIRDPARTY = 'THIRDPARTY';
    case FONEPAY = 'FONEPAY';
    case STRIPE = 'STRIPE';
    case PAYPAL = 'PAYPAL';
    case WECHAT = 'WECHAT';
    case App = 'App';
    case OTHERS = 'OTHERS';
}
