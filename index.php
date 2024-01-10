<?php

// Абстрактный класс товара
abstract class AbstractProduct
{
    protected $name;
    protected $price;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    abstract public function calculateFinalCost();
}

// Класс цифрового товара
class DigitalProduct extends AbstractProduct
{
    public function calculateFinalCost()
    {
        return $this->price / 2;
    }
}

// Класс штучного физического товара
class PhysicalProduct extends AbstractProduct
{
    protected $quantity;

    public function __construct($name, $price, $quantity)
    {
        parent::__construct($name, $price);
        $this->quantity = $quantity;
    }

    public function calculateFinalCost()
    {
        return $this->price * $this->quantity;
    }
}

// Класс товара на вес
class WeightedProduct extends AbstractProduct
{
    protected $weight;

    public function __construct($name, $price, $weight)
    {
        parent::__construct($name, $price);
        $this->weight = $weight;
    }

    public function calculateFinalCost()
    {
        return $this->price * $this->weight;
    }
}