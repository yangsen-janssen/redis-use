<?php

#获取执行sql语句
if (!function_exists('print_sql')) {

    /**
     * 使用dd输出下一段laravel DB执行的sql语句
     * @return void
     */
    function print_sql()
    {
        DB::listen(function ($query) {
            $bindings = $query->bindings;
            $sql      = $query->sql;
            foreach ($bindings as $replace) {
                $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                $sql   = preg_replace('/\?/', $value, $sql, 1);
            }
            dd($sql);
        });
    }
}

if (!function_exists('throwError')) {

    /**
     * 快速返回错误信息
     * @param $code
     * @return void
     * @throws Exception
     */
    function throwError($code)
    {
        throw new \Exception($code);
    }
}

if (!function_exists('get_array_num')) {

    /**
     * 获取二维数组个数
     * @param $array
     * @param string $key
     * @return int
     */
    function get_array_num($array, $key = '')
    {
        $i = 0;
        foreach ($array as $value) {
            if ($key) {
                $i += count($value[$key]);
            }
        }
        return $i;
    }
}

if (!function_exists('get_random_int')) {
    /**
     * 根据时间搓获取指定尾数数的随机数
     * @param int $num
     * @return int
     * @throws Exception
     */
    function get_random_int(int $num)
    {
        return time() . random_int(pow(10, ($num - 1)), pow(10, $num) - 1);
    }
}

if (!function_exists('output_file')) {
    /**
     * 输出文件到浏览器
     * @param string $file_dir
     * @param string $file_name
     * @return void
     */
    function output_file(string $file_dir, string $file_name)
    {
        //以只读和二进制模式打开文件
        $file = fopen ( $file_dir . $file_name, "rb" );

        //告诉浏览器这是一个文件流格式的文件
        Header ( "Content-type: application/octet-stream" );
        //请求范围的度量单位
        Header ( "Accept-Ranges: bytes" );
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header ( "Accept-Length: " . filesize ( $file_dir . $file_name ) );
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header ( "Content-Disposition: attachment; filename=" . $file_name );

        //读取文件内容并直接输出到浏览器
        echo fread ( $file, filesize ( $file_dir . $file_name ) );
        fclose ( $file );
        exit;
    }
}

if (!function_exists('http')) {
    /**
     * 执行爬虫
     *
     * $uri[访问地址],$isPost[是否为Post],$data[传输的数据,数组格式];
     * $cookie_file[存储Cookie地址],$set_cookie[是否存储Cookie]
     *
     * @param $uri
     * @param bool $isPost
     * @param null $data
     * @param null $cookie_file
     * @param bool $set_cookie
     * @return bool|string
     */
    function http($uri,$isPost = false,$data = NULL,$cookie_file = null,$set_cookie = false)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        if($isPost){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        } else {
            $symbol = strstr($uri,"?") ? "&" : "?";
            $uri = $data == NULL ? $uri : $uri . $symbol . http_build_query($data);
        }
        if($cookie_file != null){
            if($set_cookie === true){
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); # 存储Cookie
            }else{
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); # 携带Cookie
            }
        }
        $urlPrefix = substr($uri,0,5);
        if($urlPrefix == "https"){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($ch,CURLOPT_URL,$uri);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

if (!function_exists('get_reward_title')) {
    /**
     * 获取奖励标题
     * @param string $title
     * @return int
     */
    function get_reward_title(string $title)
    {
        $array = explode(' ', $title);
        $return_title = $title;
        if (isset($array['1'])) {
            $return_title = $array['1'];
        }
        return $return_title;
    }
}


if (!function_exists('format_num')) {
    /**
     * 保留小数
     * @param $num
     * @param int $dec
     * @param bool $strict
     * @return int
     */
    function format_num($num, $dec = 2, $strict = true)
    {
        $return_data = $num;
        if ($strict) {
            $return_data = sprintf("%.". $dec ."f", $num);
        } else {
            $return_data = round($num, $dec);
        }
        return $return_data;
    }
}

if(!function_exists('getStr')) {
    #获取随机数
    function getStr($len)
    {
        $chars_array = array(
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z",
        );
        $charsLen = count($chars_array) - 1;

        $outputstr = "";
        for ($i=0; $i<$len; $i++)
        {
            $outputstr .= $chars_array[mt_rand(0, $charsLen)];
        }
        return $outputstr;
    }
}

#异步请求
if (!function_exists('fsockopen_ext')) {
    /**
     * [fsockopen_ext description]
     * @param $method
     * @param $url
     * @param $params
     * @param $header
     * @param $timeout [description]
     * @param $wait [description]
     * @return bool|string|null [type]            [description]
     */
    function fsockopen_ext($method, $url, $params = null, $header = null, $timeout = 30, $wait = true)
    {
        try {
            $method = strtoupper($method);
            $purl   = parse_url($url);
            if (!$purl['host']) {
                return false;
            }
            isset($purl['scheme']) || $purl['scheme'] = 'http';
            isset($purl['port']) || $purl['port']     = 80;
            isset($purl['path']) || $purl['path']     = '';
            if (isset($purl['query'])) {
                $purl['query'] = '?' . $purl['query'];
            } else {
                $purl['query'] = '';
            }
            if (isset($purl['fragment'])) {
                $purl['fragment'] = '#' . $purl['fragment'];
            } else {
                $purl['fragment'] = '';
            }
            if (is_array($params)) {
                $params_str = http_build_query($params);
            } else {
                $params_str = strval($params);
            }
            if (is_array($header)) {
                if (strpos(current($header), ":") === false) {
                    $into_header = [];
                    foreach ($header as $key => $value) {
                        $into_header[] = "$key: $value";
                    }
                    $header = $into_header;
                }
                $header_str = implode("\r\n", $header);
            } else {
                $header_str = strval($header);
            }

            //创建
            $fp = fsockopen($purl['host'], $purl['port'], $errno, $errstr, $timeout);
            stream_set_timeout($fp, $timeout);
            if (!$fp || !is_resource($fp)) {
                return false;
            }

            //组合头信息
            $header = [];
            switch ($method) {
                case 'POST':
                    $header[]                = 'POST ' . $purl['path'] . $purl['query'] . $purl['fragment'] . ' HTTP/1.0';
                    $header[]                = 'Host: ' . $purl['host'];
                    $header[]                = 'Content-type: application/x-www-form-urlencoded';
                    $header[]                = 'Content-Length: ' . strlen($params_str);
                    $header_str && $header[] = $header_str;
                    $header[]                = "\r\n" . $params_str;
                    $header[]                = "\r\n";
                    break;
                case 'GET':
                    if ($params_str) {
                        $purl['query'] .= ($purl['query'] ? '&' : '?') . $params_str;
                    }
                    $header[]                = 'GET ' . $purl['path'] . $purl['query'] . $purl['fragment'] . ' HTTP/1.0';
                    $header[]                = 'Host: ' . $purl['host'];
                    $header_str && $header[] = $header_str;
                    $header[]                = "\r\n";
                    break;
            }
            $header_str = implode("\r\n", $header);

            //写入数据
            $write = fwrite($fp, $header_str);
            if ($write === false) {
                return false;
            }

            stream_set_blocking($fp, $wait ? 1 : 0);
            if (function_exists('socket_set_timeout')) {
                socket_set_timeout($fp, $timeout);
            } elseif (function_exists('stream_set_timeout')) {
                stream_set_timeout($fp, $timeout);
            }

            //等待响应
            $result = null;
            if ($wait) {
                while (!feof($fp)) {
                    $line = fread($fp, 4096);
                    $result .= $line;
                }
            }
            fclose($fp);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if(!function_exists('array_value_convert_string')) {
    /**
     * 将数据的值转换为字符串，false，null都转换为空字符串
     * @param $data array
     * @return array
     */
    function array_value_convert_string($data)
    {
        array_walk_recursive(
            $data,
            function (&$item) {
                if ($item instanceof stdClass) {
                    if ((array)$item) {
                        $item = array_value_convert_string((array)$item);
                    } else {
//
                    }
                } else {
                    if (is_bool($item)) {
                        $item = $item ? '1' : '0';
                    } else {
                        if (is_null($item)) {
                            $item = null;
                        } else {
                            $item = strval($item);
                        }
                    }
                }
            }
        );
        return $data;
    }
}

if(!function_exists('result_data_handler')) {
    /**
     * 模型结果数据处理
     * @param $data String|\Illuminate\Database\Eloquent\Collection|array
     * @return array|\Illuminate\Support\Collection
     */
    function result_data_handler($data)
    {
        if ($data instanceof \Illuminate\Contracts\Support\Jsonable) {
            $data = json_decode($data->toJson());
        } elseif ($data instanceof JsonSerializable) {
            $data = json_decode(json_encode($data->jsonSerialize()));
        } elseif ($data instanceof \Illuminate\Contracts\Support\Arrayable) {
            $data = json_decode(json_encode($data->toArray()));
        }
        if (is_array($data) || $data instanceof stdClass) {
            $data = array_value_convert_string($data);
        }
        return $data;
    }
}

if(!function_exists('success')) {
    /**
     * 返回成功数据
     * @param array $data
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    function success($data = null, $code = '200')
    {
        $successResponser = new \App\Utils\Responser();
        return $successResponser->success($data, $code);
    }
}

if(!function_exists('error')) {
    /**
     * 返回错误数据
     * @param array $data
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    function error($code = '414', $data = null)
    {
        $errorResponser = new \App\Utils\Responser();
        return $errorResponser->error($code, $data);
    }
}

