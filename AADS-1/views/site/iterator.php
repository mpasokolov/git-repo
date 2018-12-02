<?php


$this->title = 'Домашнее задание №1';
Yii::$app->name = 'Задание №2';
$bigArr = [];
$smallArr = [];

for ($i = 0; $i < 1000000; $i++) {
    $bigArr[$i] = rand(0, 1000);
}

for ($i = 0; $i < 100; $i++) {
    $smallArr[$i] = rand(0, 1000);
}

if (count($bigArr) === 1000000 && count($smallArr) === 100) {
    echo 'Генерация массивов прошла успешно!<br><br>';
}

// -------------Чтение-------------
echo "<b>Чтение</b><br><br>";

$obj = new ArrayObject( $bigArr );
$iter = $obj->getIterator();

$start = microtime(true);

while( $iter->valid() )
{
    $key = $iter -> current();
    $iter->next();
}

$result = microtime(true) - $start;

echo 'Время работа итератора на массиве из 1 000 000 элементов = ' . $result . ' cек.<br>';

$start = microtime(true);

foreach ($bigArr as $value) {
    $key = $value;
}

$result = microtime(true) - $start;

echo 'Время работа цикла foreach на массиве из 1 000 000 элементов = ' . $result . ' cек.<br><br>';

$obj = new ArrayObject( $smallArr );
$iter = $obj->getIterator();

$start = microtime(true);

while( $iter->valid() )
{
    $key = $iter -> current();
    $iter->next();
}

$result = microtime(true) - $start;

echo 'Время работа итератора на массиве из 100 элементов = ' . $result . ' cек.<br>';

$start = microtime(true);

foreach ($smallArr as $value) {
    $key = $value;
}

$result = microtime(true) - $start;

echo 'Время работа цикла foreach на массиве из 100 элементов = ' . $result . ' cек.<br><br>';


// -------------Удаление-------------
echo "<b>Удаление</b><br><br>";


$obj = new ArrayObject( $bigArr );
$iter = $obj->getIterator();

$start = microtime(true);

while( $iter->valid() )
{
    $iter -> offsetUnset($iter->key());
    $iter->next();
}

$result = microtime(true) - $start;

echo 'Время работа итератора на массиве из 1 000 000 элементов = ' . $result . ' cек.<br>';

$start = microtime(true);

foreach ($bigArr as $value) {
    array_pop($bigArr);
}

$result = microtime(true) - $start;

echo 'Время работа цикла foreach на массиве из 1 000 000 элементов = ' . $result . ' cек.<br><br>';

$obj = new ArrayObject( $smallArr );
$iter = $obj->getIterator();

$start = microtime(true);

while( $iter->valid() )
{
    $iter -> offsetUnset($iter->key());
    $iter->next();
}

$result = microtime(true) - $start;

echo 'Время работа итератора на массиве из 100 элементов = ' . $result . ' cек.<br>';

$start = microtime(true);

foreach ($smallArr as $value) {
    array_pop($smallArr);
}

$result = microtime(true) - $start;

echo 'Время работа цикла foreach на массиве из 100 элементов = ' . $result . ' cек.<br><br>';

echo 'Итог: как я ни старался итераторы работают намного медленного обычного цикла foreach.';
