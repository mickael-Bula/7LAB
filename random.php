<?php

$brands = [
    'Renault' => ['Clio', 'Mégane', 'Scénic'],
    'Peugeot' => ['308', '3008', '2008'],
    'Dacia' => ['Sandero', 'Duster'],
    'BMW' => ['X1', 'X3', 'M4'],
];

function getConstructorFromArray($brands): array
{
    $brand = array_rand($brands);   // retourne une clé au hasard (eg. BMW)
    $models = $brands[$brand];      // retourne le tableau qui se trouve e valeur de la clé précédente
    $index = array_rand($models);   // retourne un indice numérique aléatoire du tableau $models
    $model = $models[$index];       // retourne aléatoirement l'une des valeurs du tableau $models
    return [$brand, $model];
}

[$brand, $model] = getConstructorFromArray($brands);

$length = mt_rand(1600, 1900) / 1000;

echo $brand . ' ' . $model . PHP_EOL;
echo $length;