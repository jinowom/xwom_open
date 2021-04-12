<?php
namespace Services;
use yii\web\Response;
use Yii;

/**
 * 响应service
 * Class ResponseService
 *
 * @package App\Services
 */
class ResponseService
{
    /**
     * @param $code integer 错误码
     * @param array|string|bool $data 数据
     *
     * @return array
     */
    public static function response($code, $data = [])
    {
        userLog(3, 2, ErrorService::getMessage($code));
        return ['code' => $code, 'message' => ErrorService::getMessage($code), 'data' => $data];
    }

    /**
     * @param  integer $code   错误码
     * @param int   $status 状态码
     * @param array $data   数据
     *
     * @return array
     */
    public static function apiResponse($code, $message = '', $data = [])
    {
        userLog(3, 2, $message);
        return ['code' => $code, 'message' => $message, 'data' => $data];
    }

}
