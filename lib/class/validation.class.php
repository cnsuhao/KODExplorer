<?php
/**
 * 数据验证类
 *
 */
class Validation
{
    /**
     * @var array 预定义验证格式
     */
    private static $regex = array(
        'require' => '/.+/', //匹配任意字符，除了空和断行符
        'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'phone' => '/^(?:[\d]{3,4}[-*])?[0-9]{7,8}(?:[-\*][\d]{1,5})?$/',
        'mobile' => '/^[0]?[1][358][\d]{9}$/',
        'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
        'currency' => '/^\d+(\.\d+)?$/',
        'number' => '/^\d+$/',
        'zip' => '/^\d{5}$/', // 邮编首字符可以为0
        'qq' => '/^[1-9]\d{4,12}$/',
        'integer' => '/^[-\+]?\d+$/',
        'double' => '/^[-\+]?\d+(\.\d+)?$/',
        'english' => '/^[A-Za-z]+$/',
        'chinese' => '/^[\x{4e00}-\x{9fa5}]+$/u',
        'username' => '/^[\w\-\.]{2,16}$/',
        'username_cn' => '/^[\x{4e00}-\x{9fa5}]+$/u', // utf-8 编码
        'float'  => '/(^[1](\.[0]{0,2}){0,1}$)|(^[0]\.(([\d]{0,1}[1-9])|([1-9][\d]{0,1}))$)/' ,
    );

    /**
     * 验证数据项
     *
     * @param string $type  验证的数据类型名或正则式
     * @param string $value 要验证的数据
     * @param int    $min   最小长度
     * @param int    $max   最大长度
     * @return boolean
     */
    public static function check($value, $type, $min = -1, $max = -1)
    {
        $matchRegex = self::get_regex($type);
        if(preg_match($matchRegex, trim($value))) {
            $len = strlen($value) ;
            if($min >= 0 && $max > $min ) {
                return $min <= $len && $len <= $max ;
            } elseif ($min >= 0) {
                return $min <= $len ;
            } elseif ($max >= 0 ) {
                return $len <= $max ;
            }
            return true;
        }
        return false;
    }

    /**
     * 取得验证类型的正则表达式
     *
     * @param  string $name 验证类型名称,或者某类型的表达式
     * @return string
     */
    public static function get_regex($name)
    {
        if (isset(self::$regex[strtolower($name)])) {
            return self::$regex[strtolower($name)];
        } else {
            return $name;
        }
    }

    /**
     * 判断id是否合法
     *
     * @param  int  $id 
     * @return boolean
     */
    public static function valid_userid($id)
    {
        $id = intval($id) ;
        return  (($id >= 10000) && ($id < 9999999999)) ? true:false ;
    }
}

