<?php
namespace wodrow\yii2wtools\components\timer;


use common\components\tools\FileHelper;
use yii\base\Component;
use yii\log\Logger;

/**
 * Class Timer
 * 用于分析程序执行时间的小工具
 * 本工具为单例执行
 * 返回的执行时间为单位为毫秒
 */
class Timer extends Component
{
    /**
     * @var float $startTime 开始时间
     */
    private $startTime;
    /**
     * @var float $endTime 结束时间
     */
    private $endTime;
    /**
     * @var float $useTime 当前执行时间
     */
    private $useTime;
    /**
     * @var float $allTime 总执行时间
     */
    private $allTime;
    /**
     * @var string $startClass 开始类
     */
    private $startClass;
    /**
     * @var string $startLine 开始调用行
     */
    private $startLine;
    /**
     * @var string $endClass 结束类
     */
    private $endClass;
    /**
     * @var string $endLine 结束行
     */
    private $endLine;
    /**
     * @var array $record 执行记录
     */
    private $records;
    /**
     * @var float $point 时间切点
     */
    private $point;
    /**
     * @var bool $saveLog 日志是否保存为文件
     */
    public $saveLog = false;
    /**
     * @var string $logFileName 日志文件名
     * 默认在web目录下
     */
    public $logFileName = null;
    /**
     * 开始定位时间点
     */
    public function start()
    {
        $track = debug_backtrace();
        $this->startClass = isset($track[0]['file'])?$track[0]['file']:$track[0]['function'];
        $this->startLine = isset($track[0]['line'])?$track[0]['line']:$track[0]['class'];
        $this->startTime = microtime(true);
        $this->point = $this->startTime;
    }
    /**
     * 执行期，监控时间点
     */
    public function point($end=false)
    {
        $track = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $this->endTime = microtime(true);
        if (!$end) {
            $this->endClass =  isset($track[0]['file'])?$track[0]['file']:$track[0]['function'];
            $this->endLine = isset($track[0]['line'])?$track[0]['line']:$track[0]['class'];
        }else{
            $this->endClass =  isset($track[1]['file'])?$track[1]['file']:$track[1]['function'];
            $this->endLine = isset($track[1]['line'])?$track[1]['line']:$track[1]['class'];
        }
        $this->useTime = round(($this->endTime - $this->point) * 1000, 3);
        $this->allTime = round(($this->endTime - $this->startTime) * 1000, 3);
        $this->addRecord();
        if (!$end) {
            $this->point = $this->endTime;
            $this->startClass = $this->endClass;
            $this->startLine = $this->endLine;
        }
    }
    /**
     * 结束监控时间点
     */
    public function end()
    {
        $this->point(true);
        if ($this->saveLog) {
            if ($this->logFileName) {
                $this->saveToFile();
            }else{
                \Yii::$app->getLog()->getLogger()->log($this->records, Logger::LEVEL_ERROR, 'timer');
            }
        }
    }
    private function addRecord()
    {
        $array = $this->toArray();
        $this->records[] = $array;
    }
    private function toArray()
    {
        $res = [];
        $label = $this->attributesLabel();
        foreach ($this as $key => $value) {
            if (in_array($key, array_keys($label))) {
                $res[$label[$key]] = $value;
            }
        }
        return $res;
    }
    private function attributesLabel()
    {
        return [
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'startClass' => '开始文件',
            'endClass' => '结束文件',
            'startLine' => '开始行',
            'endLine' => '结束行',
            'useTime' => '当前用时',
            'allTime' => '总用时',
        ];
    }
    /**
     * @return mixed
     */
    public function getRecords()
    {
        return $this->records;
    }
    private function saveToFile()
    {
        $str = '';
        foreach ($this->records as $item) {
            $str .= json_encode($item,JSON_UNESCAPED_UNICODE);
        }
        $fileName = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $this->logFileName;
        if (!is_dir(dirname($fileName))){
            FileHelper::createDirectory(dirname($fileName));
        }
        $logfile = fopen($fileName, 'ab');
        fwrite($logfile, $str);
        fclose($logfile);
    }
}