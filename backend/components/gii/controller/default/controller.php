<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */

echo "<?php\n";
?>
/**
 * Class SystemController
 * @package <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;
 * @author  Womtech  email:chareler@163.com
 * @DateTime <?= date("Y-m-d H:i",time()) ?>
 */
namespace <?= $generator->getControllerNamespace() ?>;
//use backend\controllers\BaseController;
//class <?= StringHelper::basename($generator->controllerClass) ?> extends BaseController
class <?= StringHelper::basename($generator->controllerClass) ?> extends <?= '\\' . trim($generator->baseClass, '\\') . "\n" ?>
{
<?php foreach ($generator->getActionIDs() as $action): ?>
    public function action<?= Inflector::id2camel($action) ?>()
    {
        return $this->render('<?= $action ?>');
    }

<?php endforeach; ?>
}
