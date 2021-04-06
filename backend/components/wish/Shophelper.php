<?php
namespace backend\components\wish;


class Shophelper{

    const PURCHASE_STATUS_TRAIL = 1;
    const PURCHASE_STATUS_TRIAL_EX = 2;
    const PURCHASE_STATUS_PURCHASED = 3;
    const PURCHASE_STATUS_LICENCE_EX = 4;


    public static function getStatusLabel($code)
    {
        switch ($code) {
            case static::PURCHASE_STATUS_TRAIL:
                return 'Trial';
            case static::PURCHASE_STATUS_TRIAL_EX:
                return 'Trial Expired';
            case static::PURCHASE_STATUS_PURCHASED:
                return 'Purchased';
            case static::PURCHASE_STATUS_LICENCE_EX:
                return 'Licence Expired';
            default:
                return 'Trial';
        }
    }
}