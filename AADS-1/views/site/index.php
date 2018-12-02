<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\web\AssetBundle;

$this->title = 'Домашнее задание №1';
Yii::$app->name = 'Задание №1';

$dir = new DirectoryIterator($path);
$symbol = strripos($path, '/');
$newPath = $symbol ? substr($path, 0, $symbol) : $path;
?>

<?php foreach ($dir as $item): ?>
    <?php if (!$item->isDot()): ?>
        <div class="item">
            <?php if ($item->isDir()): ?>
                <span class="file"><?= Html::a($item -> getFilename(), ['@web', 'path' => $item -> getRealPath()]); ?></span>
                <span class="file-info"><?= date('d-m-Y H:i', $item->getCTime()) ?></span>
            <?php else: ?>
                <span class="file"><?= $item ->getFilename() ?></span>
                <span class="file-info"><?= date('d-m-Y H:i', $item->getCTime()) ?></span>
            <?php endif; ?>
        </div>
    <?php elseif($item->isDot() && $item->getFilename() === '.'): ?>
        <?php if($newPath !== $path): ?>
            <p><?= Html::a('Назад', ['@web', 'path' => $newPath], ['class' => 'back']); ?></p>
        <?php else: ?>
            <span class="back">Назад</span>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>