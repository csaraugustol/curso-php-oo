<?php

class Car
{

    public $color;
    public $year;
    public $model;

    public function __construct($color, $year, $model)
    {
        //print 'Hello ' . __CLASS__ . "\n";
        $this->color = $color;
        $this->year = $year;
        $this->model = $model;
    }

    public function run()
    {
        return $this->model . ' Car is runing...';
    }

    public function stop()
    {
        return $this->model . ' Car has stopped...';
    }

    public function __destruct()
    {
        print 'Removing object ' . __CLASS__ . "\n";
    }
}

$car = new Car('Yellow', 2021, 'Camaro');
print $car->model;
// $car->color = 'Yellow';
// $car->year = 2021;
// $car->model = 'Camaro';

// print $car->run();
// print $car->stop();

$car2 = new Car('Blue', 2021, 'Mustang');
print $car2->model;
// $car2->color = 'Blue';
// $car2->year = 2021;
// $car2->model = 'Mustang';

// print $car2->run();
// print $car2->stop();
