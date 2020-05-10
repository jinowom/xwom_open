<?php
namespace wodrow\wajaxcrud\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;
/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class Generator extends \yii\gii\Generator
{
    public $showName = 'wodrow wajaxcrud generator';
    public $modelClass;
    public $controllerClass;
    public $viewPath;
    public $baseControllerClass = 'yii\web\Controller';
    public $searchModelClass = '';
    public $editableFields;
    public $dateRangeFields;
    public $thumbImageFields;
    public $isDesc = true;
    public $statusField = 'status';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->showName;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model with template for Single Page Ajax Administration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['controllerClass', 'modelClass', 'searchModelClass', 'baseControllerClass', 'viewPath', 'editableFields', 'dateRangeFields', 'thumbImageFields', 'statusField'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'controllerClass', 'baseControllerClass'], 'required'],
            [['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
            [['modelClass', 'controllerClass', 'baseControllerClass', 'searchModelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
            [['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
            [['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
            [['controllerClass'], 'match', 'pattern' => '/(^|\\\\)[A-Z][^\\\\]+Controller$/', 'message' => 'Controller class name must start with an uppercase letter.'],
            [['controllerClass', 'searchModelClass'], 'validateNewClass'],
            [['modelClass'], 'validateModelClass'],
            [['enableI18N'], 'boolean'],
            [['isDesc'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
            ['viewPath', 'safe'],
            ['editableFields', 'validateEditableFields'],
            ['dateRangeFields', 'validateDateRangeFields'],
            ['thumbImageFields', 'validateThumbImageFields'],
            ['statusField', 'validateStatusField'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
            'controllerClass' => 'Controller Class',
            'viewPath' => 'View Path',
            'baseControllerClass' => 'Base Controller Class',
            'searchModelClass' => 'Search Model Class',
            'editableFields' => 'Editable Fields',
            'dateRangeFields' => 'Date Range Fields',
            'thumbIamgeFields' => 'Thumb Image Fields',
            'statusFields' => 'Status Field',
        ]);
    }


    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
                You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
             'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>app\controllers\PostController</code>),
                and class name should be in CamelCase with an uppercase first letter. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'viewPath' => 'Specify the directory for storing the view scripts for the controller. You may use path alias here, e.g.,
                <code>/var/www/basic/controllers/views/post</code>, <code>@app/views/post</code>. If not set, it will default
                to <code>@app/views/ControllerID</code>',
            'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
                You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
            'searchModelClass' => 'This is the name of the search model class to be generated. You should provide a fully
                qualified namespaced class name, e.g., <code>app\models\PostSearch</code>.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['baseControllerClass']);
    }

    /**
     * Checks if model class is valid
     */
    public function validateModelClass()
    {
        /* @var $class ActiveRecord */     
        $class = $this->modelClass;
        $pk = $class::primaryKey();
        if (empty($pk)) {
            $this->addError('modelClass', "The table associated with $class must have primary key(s).");
        }
    }

    public function validateStatusField()
    {
        $class = $this->modelClass;
        $field = $this->statusField;
        $field = trim($field);
        $pk = $class::primaryKey();
        if (in_array($field, $pk)) {
            $this->addError('dateRangeFields', "primary key(s) can not be status");
        }
        if (!in_array($field, (new $class)->attributes())) {
            $this->addError('dateRangeFields', "field '{$field}' not found!");
        }
    }

    public function validateEditableFields()
    {
        $class = $this->modelClass;
        $fields = explode(',', $this->editableFields);
        $pk = $class::primaryKey();
        foreach ($fields as $k => $v) {
            if (!$v)continue;
            $v = trim($v);
            if (in_array($v, $pk)) {
                $this->addError('editableFields', "primary key(s) can not be editable");
            }
            if (!in_array($v, (new $class)->attributes())) {
                $this->addError('editableFields', "field '{$v}' not found!");
            }
            if (!$this->checkResuing()){
                $this->addError('editableFields', "can not be editable");
            }
        }
    }

    public function validateDateRangeFields()
    {
        $class = $this->modelClass;
        $fields = explode(',', $this->dateRangeFields);
        $pk = $class::primaryKey();
        foreach ($fields as $k => $v) {
            if (!$v)continue;
            $v = trim($v);
            if (in_array($v, $pk)) {
                $this->addError('dateRangeFields', "primary key(s) can not be date range");
            }
            if (!in_array($v, (new $class)->attributes())) {
                $this->addError('dateRangeFields', "field '{$v}' not found!");
            }
            if (!$this->checkResuing()) {
                $this->addError('dateRangeFields', "can not be date range");
            }
        }
    }

    public function validateThumbImageFields()
    {
        $class = $this->modelClass;
        $fields = explode(',', $this->thumbImageFields);
        $pk = $class::primaryKey();
        foreach ($fields as $k => $v) {
            if (!$v)continue;
            $v = trim($v);
            if (in_array($v, $pk)) {
                $this->addError('thumbImageFields', "primary key(s) can not be thumb image");
            }
            if (!in_array($v, (new $class)->attributes())) {
                $this->addError('thumbImageFields', "field '{$v}' not found!");
            }
            if (!$this->checkResuing()) {
                $this->addError('thumbImageFields', "can not be thumb image");
            }
        }
    }

    public function checkResuing()
    {
        $a = $this->generateDateRangeFields();
        $b = $this->generateEditableFields();
        $c = $this->generateThumbImageFields();
        $d = $this->statusField?[$this->statusField]:[];
        $x = array_merge(array_merge(array_merge($a, $b), $c), $d);
        $t1 = count($a) + count($b) + count($c) + count($d);
        $t2 = count($x);
        if ($t1 > $t2){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');
        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr(substr($this->controllerClass, $pos + 1), 0, -10);

        return Inflector::camel2id($class);
    }

    /**
     * @return string the controller view path
     */
    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/views/' . $this->getControllerID());
        } else {
            return Yii::getAlias($this->viewPath);
        }
    }

    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->modelClass;
        $pk = $class::primaryKey();

        return $pk[0];
    }

    public function generateEditableFields()
    {
        $fields = explode(',', $this->editableFields);
        foreach ($fields as $k => $v) {
            if (!$v){
                unset($fields[$k]);
            }
        }
        return $fields;
    }

    public function generateDateRangeFields()
    {
        $fields = explode(',', $this->dateRangeFields);
        foreach ($fields as $k => $v) {
            if (!$v){
                unset($fields[$k]);
            }
        }
        return $fields;
    }

    public function generateThumbImageFields()
    {
        $fields = explode(',', $this->thumbImageFields);
        foreach ($fields as $k => $v) {
            if (!$v){
                unset($fields[$k]);
            }
        }
        return $fields;
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            } else {
                return "\$form->field(\$model, '$attribute')";
            }
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } elseif ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$form->field(\$model, '$attribute')->dropDownList("
                    . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return "\$form->field(\$model, '$attribute')->$input()";
            } else {
                return "\$form->field(\$model, '$attribute')->$input(['maxlength' => true])";
            }
        }
    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } else {
            return "\$form->field(\$model, '$attribute')";
        }
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        } elseif ($column->type === 'text') {
            return 'ntext';
        } elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        } elseif (stripos($column->name, 'email') !== false) {
            return 'email';
        } elseif (stripos($column->name, 'url') !== false) {
            return 'url';
        } else {
            return 'text';
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            if (in_array($column->name, $this->generateDateRangeFields())){
                // [['created_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
                $types['match']['columns'][] = $column->name;
                $types['match']['pattern'] = '/^.+\s\-\s.+$/';
            }else{
                switch ($column->type) {
                    case Schema::TYPE_SMALLINT:
                    case Schema::TYPE_INTEGER:
                    case Schema::TYPE_BIGINT:
                        $types['integer']['columns'][] = $column->name;
                        break;
                    case Schema::TYPE_BOOLEAN:
                        $types['boolean']['columns'][] = $column->name;
                        break;
                    case Schema::TYPE_FLOAT:
                    case Schema::TYPE_DOUBLE:
                    case Schema::TYPE_DECIMAL:
                    case Schema::TYPE_MONEY:
                        $types['number']['columns'][] = $column->name;
                        break;
                    case Schema::TYPE_DATE:
                    case Schema::TYPE_TIME:
                    case Schema::TYPE_DATETIME:
                    case Schema::TYPE_TIMESTAMP:
                    default:
                        $types['safe']['columns'][] = $column->name;
                        break;
                }
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            if ($type == 'match'){
                $rules[] = "[['" . implode("', '", $columns['columns']) . "'], '$type', 'pattern' => '{$columns['pattern']}']";
            }else{
                $rules[] = "[['" . implode("', '", $columns['columns']) . "'], '$type']";
            }
        }

        return $rules;
    }

    /**
     * @return array searchable attributes
     */
    public function getSearchAttributes()
    {
        return $this->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels()
    {
        /* @var $model \yii\base\Model */
        $model = new $this->modelClass();
        $attributeLabels = $model->attributeLabels();
        $labels = [];
        foreach ($this->getColumnNames() as $name) {
            if (isset($attributeLabels[$name])) {
                $labels[$name] = $attributeLabels[$name];
            } else {
                if (!strcasecmp($name, 'id')) {
                    $labels[$name] = 'ID';
                } else {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
                    }
                    $labels[$name] = $label;
                }
            }
        }

        return $labels;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $conditions = [];
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            if (in_array($column, $this->generateDateRangeFields())){
                $conditions[] = <<<HTML
if ( ! is_null(\$this->{$column}) && strpos(\$this->{$column}, ' - ') !== false ) {
            list(\$s, \$e) = explode(' - ', \$this->{$column});
            \$query->andFilterWhere(['between', '{$column}', strtotime(\$s), strtotime(\$e)]);
        }\n
HTML;

            }else{
                switch ($type) {
                    case Schema::TYPE_SMALLINT:
                    case Schema::TYPE_INTEGER:
                    case Schema::TYPE_BIGINT:
                    case Schema::TYPE_BOOLEAN:
                    case Schema::TYPE_FLOAT:
                    case Schema::TYPE_DOUBLE:
                    case Schema::TYPE_DECIMAL:
                    case Schema::TYPE_MONEY:
                    case Schema::TYPE_DATE:
                    case Schema::TYPE_TIME:
                    case Schema::TYPE_DATETIME:
                    case Schema::TYPE_TIMESTAMP:
                        $hashConditions[] = "'{$column}' => \$this->{$column},";
                        break;
                    default:
                        $likeConditions[] = "\$this->filterLike(\$query, '{$column}');";
                        break;
                }
            }
        }

        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = implode("\n" . str_repeat(' ', 8), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            } else {
                return "'id' => \$model->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'$pk' => (string)\$model->$pk";
                } else {
                    $params[] = "'$pk' => \$model->$pk";
                }
            }

            return implode(', ', $params);
        }
    }

    /**
     * Generates action parameters
     * @return string
     */
    public function generateActionParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            return '$id';
        } else {
            return '$' . implode(', $', $pks);
        }
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function generateActionParamComments()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (($table = $this->getTableSchema()) === false) {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . (substr(strtolower($pk), -2) == 'id' ? 'integer' : 'string') . ' $' . $pk;
            }

            return $params;
        }
        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        } else {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
            }

            return $params;
        }
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return boolean|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }

    /**
     * @return array model column names
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        } else {
            /* @var $model \yii\base\Model */
            $model = new $class();

            return $model->attributes();
        }
    }
}
