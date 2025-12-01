<?php

namespace App\Helpers;

class OrderHelper
{
    /**
     * Получить название способа оплаты
     */
    public static function getPaymentMethodLabel($method): string
    {
        $labels = [
            'cash' => 'Наличными при получении',
            'cash_on_delivery' => 'Наличными при получении',
            'card' => 'Банковской картой',
            'credit_card' => 'Банковской картой',
            'transfer' => 'Банковский перевод',
            'bank_transfer' => 'Банковский перевод',
        ];
        
        return $labels[$method] ?? $method;
    }
    
    /**
     * Получить название способа доставки
     */
    public static function getDeliveryMethodLabel($method): string
    {
        $labels = [
            'pickup' => 'Самовывоз',
            'courier' => 'Курьерская доставка',
            'express_delivery' => 'Экспресс-доставка',
            'transport' => 'Транспортная компания',
            'standard_delivery' => 'Стандартная доставка',
        ];
        
        return $labels[$method] ?? $method;
    }
    
    /**
     * Нормализовать способ оплаты (привести к новому формату)
     */
    public static function normalizePaymentMethod($method): string
    {
        $mapping = [
            'cash_on_delivery' => 'cash',
            'credit_card' => 'card',
            'bank_transfer' => 'transfer',
        ];
        
        return $mapping[$method] ?? $method;
    }
    
    /**
     * Нормализовать способ доставки (привести к новому формату)
     */
    public static function normalizeDeliveryMethod($method): string
    {
        $mapping = [
            'express_delivery' => 'courier',
            'standard_delivery' => 'transport',
        ];
        
        return $mapping[$method] ?? $method;
    }
}

