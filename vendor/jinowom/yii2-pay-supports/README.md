<h1 align="center">yii2-Pay-Supports</h1>


handle with array/config/log/guzzle etc.

## About log

```PHP
use jinowom\Supports\Logger as Log;
use Monolog\Logger;

class ApplicationLogger
{
    private static $logger;

    /**
     * Forward call.
     *
     * @author jinowom <chareler@163.com>
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args)
    {
        return call_user_func_array([self::getLogger(), $method], $args);
    }

    /**
     * Forward call.
     *
     * @author jinowom <chareler@163.com>
     *
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        return call_user_func_array([self::getLogger(), $method], $args);
    }

    /**
     * Make a default log instance.
     *
     * @author jinowom <chareler@163.com>
     *
     * @return Log
     */
    public static function getLogger()
    {
        if (! self::$logger instanceof Logger) {
            self::$logger = new Log();
        }   

        return self::$logger;
    }
}
```

### Usage

After registerLog, you can use Log service:

```PHP

ApplicationLogger::debug('test', ['test log']);
```
