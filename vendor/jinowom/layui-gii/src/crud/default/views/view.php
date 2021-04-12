<?php
/* @var $generator jinowom\Layuigii\crud\Generator */
/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
?>
<?= "<?php ";?>/* @var $model <?= $generator->modelClass ?> */<?= " ?>";?>
<?php echo "\n" ?>
<div class="panel panel-default">
    <div class="panel-body">
    <table class="layui-table">
        <?php
        $model = new $generator->modelClass();
        foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                $label = $model->getAttributeLabel($attribute);
                $item = <<<ITEM
                    <tr>
                        <td>$label</td>
                        <td>$attribute</td>
                    </tr>
ITEM;
                $item .= "\n";
                echo $item;
            }
        } ?>
        </table>
   </div>
</div>