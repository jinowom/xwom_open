<?php
/**
 * 获取所有路由
 * @author xinnianq 2017-10-11
 */
namespace wodrow\yii2wtools\tools;
use Yii;
class Routes {
	
    /**
     * Get list of application routes [获取应用的所有路由]
     * @return array
     */
    public static function getAppRoutes($module = null)
    {
        if ($module === null) {
            $module = Yii::$app;
        } elseif (is_string($module)) {
            $module = Yii::$app->getModule($module);
        }
        $key = [__METHOD__, $module->getUniqueId()];
        // $cache = Configs::instance()->cache;
//        if ($cache === null || ($result = $cache->get($key)) === false) {
        $result = [];
        self::getRouteRecrusive($module, $result);
//            if ($cache !== null) {
//                $cache->set($key, $result, Configs::instance()->cacheDuration, new TagDependency([
//                    'tags' => self::CACHE_TAG,
//                ]));
//            }
        // }
        return $result;
    }
    /**
     * 递归Get route(s)
     * @param \yii\base\Module $module
     * @param array $result
     */
    protected static function getRouteRecrusive($module, &$result)
    {
        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
//            foreach ($module->getModules() as $id => $child) {
//                if (($child = $module->getModule($id)) !== null) {
//                    self::getRouteRecrusive($child, $result);
//                }
//            }
            foreach ($module->controllerMap as $id => $type) {
                self::getControllerActions($type, $id, $module, $result);
            }
            $namespace = trim($module->controllerNamespace, '\\') . '\\';
            self::getControllerFiles($module, $namespace, '', $result);
            //$all = '/' . ltrim($module->uniqueId . '/*', '/');  //###############################
            //$result[$all] = $all;
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }
    /**
     * Get list controller under module
     * @param \yii\base\Module $module
     * @param string $namespace
     * @param string $prefix
     * @param mixed $result
     * @return mixed
     */
    protected static function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = Yii::getAlias('@' . str_replace('\\', '/', $namespace), false);
        $token = "Get controllers from '$path'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file) && preg_match('%^[a-z0-9_/]+$%i', $file . '/')) {
                    self::getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $baseName = substr(basename($file), 0, -14);
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $baseName));
                    $id = ltrim(str_replace(' ', '-', $name), '-');
                    $className = $namespace . $baseName . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        self::getControllerActions($className, $prefix . $id, $module, $result);
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }
    /**
     * Get list action of controller 获取controller的action
     * @param mixed $type
     * @param string $id
     * @param \yii\base\Module $module
     * @param string $result
     */
    protected static function getControllerActions($type, $id, $module, &$result)
    {
        $token = "Create controller with cofig=" . \yii\helpers\VarDumper::dumpAsString($type) . " and id='$id'";
        Yii::beginProfile($token, __METHOD__);
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module]);
            self::getActionRoutes($controller, $result);
            //$all = "/{$controller->uniqueId}/*"; //#####################################################
            //$result[$all] = $all;
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }
    /**
     * Get route of action
     * @param \yii\base\Controller $controller
     * @param array $result all controller action.
     */
    protected static function getActionRoutes($controller, &$result)
    {
        $token = "Get actions of controller '" . $controller->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/' . $controller->uniqueId . '/';
//            foreach ($controller->actions() as $id => $value) {
//                //$result[$prefix . $id] = $prefix . $id; //###########################################
//                $result[$controller->uniqueId][$prefix . $id][0] = $prefix . $id;
//                $result[$controller->uniqueId][$prefix . $id][1] = '';
//            }
            $class = new \ReflectionClass($controller);
            $result['/'.$controller->uniqueId.'/']['controllerDescription'] = self::getHelpSummary($controller); //控制器描述
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                    $id = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                    //$result[$id] = $id; //####################################
                    $result['/'.$controller->uniqueId.'/']['route'][$id][0] = $id;
                    //获取方法描述开始#############
                    $summary = self::getActionHelpSummary($controller,$controller->createAction(ltrim(str_replace(' ', '-', $name), '-')));
                    $result['/'.$controller->uniqueId.'/']['route'][$id][1] = $summary;
                    //获取方法描述结束#############
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage().$exc->getLine().'##'.$exc->getCode().'@@', __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }
    /**
     * 获取控制器描述
     * Returns one-line short summary describing this controller.
     *
     * You may override this method to return customized summary.
     * The default implementation returns first line from the PHPDoc comment.
     *
     * @return string
     */
    public static function getHelpSummary($controller)
    {
        return self::parseDocCommentSummary(new \ReflectionClass($controller));
    }
    //获取方法描述开始#############
    /**
     * Returns a one-line short summary describing the specified action.
     * @param Action $action action to get summary for
     * @return string a one-line short summary describing the specified action.
     */
    public static function getActionHelpSummary($controller,$action)
    {
        return self::parseDocCommentSummary(self::getActionMethodReflection($controller,$action));
    }
    /**
     * @param Action $action
     * @return \ReflectionMethod
     */
    protected static function getActionMethodReflection($controller,$action)
    {
        $_reflections = [];
        if (!isset($_reflections[$action->id])) {
            if ($action instanceof \yii\base\InlineAction) {
                $_reflections[$action->id] = new \ReflectionMethod($controller, $action->actionMethod);
            } else {
                $_reflections[$action->id] = new \ReflectionMethod($action, 'run');
            }
        }
        return $_reflections[$action->id];
    }
    /**
     * Returns the first line of docblock.
     *
     * @param \Reflector $reflection
     * @return string
     */
    protected static function parseDocCommentSummary($reflection)
    {
        $docLines = preg_split('~\R~u', $reflection->getDocComment());
        if (isset($docLines[1])) {
            return trim($docLines[1], "\t *");
        }
        return '';
    }
    //获取方法描述结束#############
}