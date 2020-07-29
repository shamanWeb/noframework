<?php
/**
 * @var AppException $exception
 */

use App\core\AppException;
use App\core\Route;

?>
<div class="cover">
    <h1>Application Exception
        <small><?php echo $exception->getFile(); ?>, line <?php echo $exception->getLine(); ?></small>
    </h1>
    <p class="lead"><?php echo $exception->getMessage(); ?></p>

    <div class="lead">
        <a href="<?php echo Route::createUrl('/'); ?>" class="btn btn-lg btn-primary">
            <i class="glyphicon glyphicon-arrow-left"></i> Go Home</a>
    </div>

    <?php var_dump($exception); ?>
</div>