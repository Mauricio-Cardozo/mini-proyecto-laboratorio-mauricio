<?php

final class PriceCalculator
{
    // COMPOSICION: La calculadora TIENE una estrategia inyectada
    public function __construct(private PricingStrategy $strategy)
    {
    }

    // El cálculo base se delega por completo a la estrategia activa (Polimorfismo)
    public function calculate(float $amount): float
    {
        return $this->strategy->calculate($amount);
    }

    // Resolvemos el problema del IVA en un solo lugar genérico
    public function calculateWithIva(float $amount): float
    {
        return $this->calculate($amount) * 1.21;
    }
}
