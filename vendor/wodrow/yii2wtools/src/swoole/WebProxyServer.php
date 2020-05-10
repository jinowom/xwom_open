<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-8-8
 * Time: 下午5:08
 */

namespace wodrow\yii2wtools\swoole;


use yii\base\Component;

/**
 * Web代理服务器(支持http/https)
 * @author zhjx922
 */
class WebProxyServer extends Component
{
    public $server_host = '0.0.0.0';
    public $server_port = '9510';
    public $buffer_output_size = 134217728; //必须为数字

    private $_client = [];
    /**
     * @var \swoole_server $_server
     */
    private $_server;

    /**
     * 日志打印
     * @author zhjx922
     * @param string $message
     */
    protected function log($message)
    {
        echo $message . PHP_EOL;
    }

    /**
     * 获取代理ip
     * @author zhjx922
     */
    protected function getLocalIp()
    {
        //获取代理IP
        $ipList = swoole_get_local_ip();
        foreach($ipList as $interface => $ip) {
            $this->log("{$interface}:{$ip}");
        }
    }

    /**
     * 初始化
     * @author zhjx922
     */
    public function init()
    {
        $this->getLocalIp();

        $this->_server = new \swoole_server($this->server_host, $this->server_port);

        $this->_server->set([
            'buffer_output_size' => $this->buffer_output_size,
        ]);
    }

    /**
     * 跑起来
     * @author zhjx922
     */
    public function run()
    {
        $this->init();

        $this->_server->on('connect', function ($server, $fd){
            $this->log("Server connection open: {$fd}");
        });

        $this->_server->on('receive', function ($server, $fd, $reactor_id, $buffer){

            //判断是否为新连接
            if(!isset($this->_client[$fd])) {
                //判断代理模式
                list($method, $url) = explode(' ', $buffer, 3);
                $url = parse_url($url);

                //ipv6为啥外面还有个方括号？
                if(strpos($url['host'], ']')) {
                    $url['host'] = str_replace(['[', ']'], '', $url['host']);
                }

                //解析host+port
                $host = $url['host'];
                $port = isset($url['port']) ? $url['port'] : 80;

                //ipv4/v6处理
                $tcpMode = strpos($url['host'], ':') !== false ? SWOOLE_SOCK_TCP6 : SWOOLE_SOCK_TCP;
                $this->_client[$fd] = new \swoole_client($tcpMode, SWOOLE_SOCK_ASYNC);

                if($method == 'CONNECT')
                {
                    $this->_client[$fd]->on("connect", function (\swoole_client $cli) use ($fd) {
                        $this->log("隧道模式-连接成功!");
                        //告诉客户端准备就绪，可以继续发包
                        $this->_server->send($fd, "HTTP/1.1 200 Connection Established\r\n\r\n");
                    });
                } else {
                    $this->_client[$fd]->on("connect", function(\swoole_client $cli) use ($buffer) {
                        $this->log("正常模式-连接成功!");
                        //直接转发数据
                        $cli->send($buffer);
                    });
                }

                $this->_client[$fd]->on("receive", function(\swoole_client $cli, $buffer) use ($fd){
                    //将收到的数据转发到客户端
                    if($this->_server->exist($fd)) {
                        $this->_server->send($fd, $buffer);
                    }
                });

                $this->_client[$fd]->on("error", function(\swoole_client $cli) use ($fd){
                    $this->log("Client {$fd} error");
                });

                $this->_client[$fd]->on("close", function(\swoole_client $cli) use ($fd){
                    $this->log("Client {$fd} connection close");
                });

                $this->_client[$fd]->connect($host, $port);
            } else {
                //已连接，正常转发数据
                if($this->_client[$fd]->isConnected()) {
                    $this->_client[$fd]->send($buffer);
                }
            }

        });

        $this->_server->on('close', function ($server, $fd) {
            $this->log("Server connection close: {$fd}");
            unset($this->_client[$fd]);
        });

        $this->_server->start();
    }
}