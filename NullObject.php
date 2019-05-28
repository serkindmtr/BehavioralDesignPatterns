<?php

/**
 * WIKI:
 * https://ru.wikipedia.org/wiki/Null_object_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 */

interface Animal {
    public function makeSound();
}

class Dog implements Animal {
    public function makeSound() { 
        echo "Woof.."; 
    }
}

class Cat implements Animal {
    public function makeSound() { 
        echo "Meowww.."; 
    }
}

class NullAnimal implements Animal {
    public function makeSound() { 
        // silence...
    }
}

$animalType = 'elephant';
switch($animalType) {
    case 'dog':
        $animal = new Dog();
        break;
    case 'cat':
        $animal = new Cat();
        break;
    default:
        $animal = new NullAnimal();
        break;
}
$animal->makeSound(); // ..the null animal makes no sound