<?php
/**
 * This is the template for generating a controller class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>
/**
 * Default controller for the `<?= $generator->moduleID ?>` module
 * @author  Womtech  email:chareler@163.com
 * @DateTime <?= date("Y-m-d H:i",time()) ?>
 */
 
namespace <?= $generator->getControllerNamespace() ?>;
//use yii\web\Controller;
use backend\controllers\BaseController;

//class DefaultController extends Controller
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //查看是否调用成功。注释掉下面一行即可验证
        //echo 'hello resource modules, 当您看到这个消息，说明调用子模块成功</br>';
        return $this->render('index');
    }
}
