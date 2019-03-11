<?php
namespace helper;

/**
 * socket 帮助类
 * Class SocketHelper
 */
final class ExceptionHelper extends \Exception
{
    public $message = '';
    public $httpCode = 500;
    public $code = 0;

    public function __construct($message = '', $httpCode = 500, $code = 0, $arrLogInfo = []){
        $this->httpCode = $httpCode;
        $this->message = $message;
        $this->code = $code;
        if (!empty($arrLogInfo)) {
            LogHelper::printError($arrLogInfo);
        }
    }
}
