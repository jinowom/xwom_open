<?php
namespace wodrow\yii2wtools\components\gaode;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;

class WebService extends Component
{
    /**
     * @var string
     */
    public $key;

    /**
     * @throws InvalidConfigException
     */

    public function init()
    {
        if ($this->key === null) {
            throw new InvalidConfigException('The "key" property must be set.');
        }
    }

    /**
     * @param $city
     * @param string $format
     * @return mixed|string
     */
    public function getLiveWeather($city, $format = 'json')
    {
        return $this->getWeather($city, 'base', $format);
    }
    /**
     * @param $city
     * @param string $format
     * @return mixed|string
     */
    public function getForecastsWeather($city, $format = 'json')
    {
        return $this->getWeather($city, 'all', $format);
    }
    /**
     * @param $city
     * @param string $type
     * @param string $format
     * @return mixed|string
     */
    public function getWeather($city, $type = 'base', $format = 'json')
    {
        if (!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidParamException('Invalid response format: '.$format);
        }
        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidParamException('Invalid type value(base/all): '.$type);
        }
        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => \strtolower($format),
            'extensions' => \strtolower($type),
        ]);
        try {
            $client = new Client();
            $response = $client->get('https://restapi.amap.com/v3/weather/weatherInfo', [
                'query' => $query,
            ])->getBody()->getContents();
            return 'json' === $format ? json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }
    }
}