<?php
/* @var $className string class name */
echo "<?php\n";
?>
namespace <?= $generator->ns ?>;

use Yii;

class <?= $className ?> extends <?= '\\' . ltrim($generator->ns, '\\') .'\\base\\'.$baseModelClass. "\n" ?>
{

}
