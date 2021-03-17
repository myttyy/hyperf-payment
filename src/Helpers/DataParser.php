<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Helpers;

/**
 * Class DataParser
 *  myttyy\Hyperf\Payment\Helpers
 * 
 *2019/3/30 8:12 PM
 * 1.0.1
 * 数据格式化
 *
 */
class DataParser
{
    /**
     * 输出xml字符
     * @param array $values
     * @return string|bool
     **/
    public static function toXml($values)
    {
        if (!is_array($values) || count($values) <= 0) {
            return false;
        }

        $xml = '<xml>';
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            } else {
                $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 将xml转为array
     * @param mixed $xml
     * @return array|false
     */
    public static function toArray($xml)
    {
        if (!$xml) {
            return false;
        }

        if (is_array($xml)) {

            // myttyy 兼容 Hyperf\Guzzle 请求后返回数组
            $data = [];
            foreach ($xml as $k => $v) {
                $data[$k] = !empty($v) ? $v : '';
            }
            unset($xml);
            return $data;
        }

        // 检查xml是否合法
        $xml_parser = xml_parser_create();

        if (!xml_parse($xml_parser, $xml, true)) {
            xml_parser_free($xml_parser);
            return false;
        }

        //将XML转为array
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $data;
    }
}
