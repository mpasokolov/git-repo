<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 17/10/2018
 * Time: 19:02
 */

?>
<div class="user_history">
    <h2>История просмотров</h2>
    <ol>
    <?php foreach ($history as $value): ?>
        <li><a><?php echo $value?></a></li><br>
    <?php endforeach; ?>
    </ol>
</div>