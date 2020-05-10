<?php

use Yii;
use nickdenry\grid\toggle\helpers\ModelHelper;
use nickdenry\grid\toggle\tests\_data\ToggleModel;
use nickdenry\grid\toggle\tests\_data\StringToggleModel;
use nickdenry\grid\toggle\tests\_data\IntToggleModel;

class ModelHelperTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGetToggleValue()
    {
        $module = Yii::$app->getModule('roundSwitch');
        $toggleModel = new ToggleModel();
        $stringToggleModel = new StringToggleModel();
        $intToggleModel = new IntToggleModel();
        $this->assertEquals(true, ModelHelper::getToggleValue($toggleModel, 'is_published', $module::SWITCH_KEY_ON));
        $this->assertEquals(false, ModelHelper::getToggleValue($toggleModel, 'is_published', $module::SWITCH_KEY_OFF));
        $this->assertEquals('yes', ModelHelper::getToggleValue($stringToggleModel, 'is_published', $module::SWITCH_KEY_ON));
        $this->assertEquals('no', ModelHelper::getToggleValue($stringToggleModel, 'is_published', $module::SWITCH_KEY_OFF));
        $this->assertEquals(2, ModelHelper::getToggleValue($intToggleModel, 'is_published', $module::SWITCH_KEY_ON));
        $this->assertEquals(3, ModelHelper::getToggleValue($intToggleModel, 'is_published', $module::SWITCH_KEY_OFF));
    }

    public function testGetToggleValueWrongModelException() {
        $module = Yii::$app->getModule('roundSwitch');
        $testWrongModel = 'non-model';
        $this->expectException(\Error::class);
        $this->assertEquals('yes', ModelHelper::getToggleValue($testWrongModel, 'wrong_attribute', $module::SWITCH_KEY_ON));
    }

    public function testGetToggleValueModelWrongAttributeException() {
        $module = Yii::$app->getModule('roundSwitch');
        $stringModel = new StringToggleModel();
        $this->expectException(\yii\base\InvalidConfigException::class);
        $this->assertEquals('yes', ModelHelper::getToggleValue($stringModel, 'wrong_attribute', $module::SWITCH_KEY_ON));
    }

    public function testGetToggleValueModelWrongKeyException() {
        $module = Yii::$app->getModule('roundSwitch');
        $stringModel = new StringToggleModel();
        $this->expectException(\PHPUnit_Framework_Exception::class);
        $this->assertEquals('yes', ModelHelper::getToggleValue($stringModel, 'is_published', 'wrong_key'));
    }

    public function testIsCheckedToggleModel()
    {
        $toggleModel = new ToggleModel();
        $toggleModel->is_published = true;
        $this->assertEquals(true, ModelHelper::isChecked($toggleModel, 'is_published'));
        $toggleModel->is_published = false;
        $this->assertEquals(false, ModelHelper::isChecked($toggleModel, 'is_published'));

    }

    public function testIsCheckedToggleModelWrongAttributeException()
    {
        $toggleModel = new ToggleModel();
        $toggleModel->is_published = true;
        $this->expectException(\yii\base\UnknownPropertyException::class);
        $this->assertEquals(true, ModelHelper::isChecked($toggleModel, 'wrong_attribute'));
    }

    public function testIsCheckedStringToggleModel()
    {
        $stringModel = new StringToggleModel();
        $stringModel->is_published = 'yes';
        $this->assertEquals(true, ModelHelper::isChecked($stringModel, 'is_published'));
        $stringModel->is_published = 'no';
        $this->assertEquals(false, ModelHelper::isChecked($stringModel, 'is_published'));
    }

    public function testIsCheckedIntToggleModel()
    {
        $intModel = new IntToggleModel();
        $intModel->is_published = 2;
        $this->assertEquals(true, ModelHelper::isChecked($intModel, 'is_published'));
        $intModel->is_published = 3;
        $this->assertEquals(false, ModelHelper::isChecked($intModel, 'is_published'));
    }
}