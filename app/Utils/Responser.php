<?php

namespace App\Utils;

/**
 * 自定义返回json
 * Class Responser
 * @package App\Utils
 */
class Responser
{
    private $code = '200';
    private $status = '1';
    private $data = [];

    /**
     * 响应成功消息
     *
     * @param array $data
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], $code = '200')
    {
        $this->data = $data;
        $this->code = $code;
        $this->status = '1';
        return $this->response($this->process());
    }

    /**
     * 响应错误消息
     *
     * @param string $code 错误码
     * @param array $data 错误时需要返回的数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($code = '414', $data = [])
    {
        $this->data = $data;
        $this->code = $code;
        $this->status = '0';
        return $this->response($this->process());
    }

    /**
     * 处理为需要的数据格式
     * @access public
     * @return array
     */
    private function rule()
    {
        return [
            'status' => $this->status,
            'code'   => self::getMessage($this->code) ? $this->code : '414',
            'desc'   => self::getMessage($this->code) ? self::getMessage($this->code) : $this->code,
            'data'   => result_data_handler($this->data),
        ];
    }

    /**
     * 处理为json数据
     * @access public
     * @return string
     */
    private function process()
    {
        return json_encode($this->rule(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * 返回数据
     * @access public
     * @param string $json
     * @return \Illuminate\Http\JsonResponse
     */
    private function response(string $json)
    {
        return response($json)->header('Content-Type', 'application/json')->header('Cache-Control', 'no-cache, must-revalidate');
    }

    /**
     * 获取错误码
     * @return array
     **/
    private function getCodes()
    {
        $code_raw = file_get_contents(__DIR__."/json/code.json");
        return json_decode($code_raw, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取错误信息
     * @param string $code
     * @return bool
     */
    private function getMessage(string $code)
    {
        $json = $this->getCodes();

        if (isset($json[$code]))
        {
            return $json[$code];
        } else {
            return false;
        }
    }
}
