<?php
class Util
{
    public static function numToChar($num)
    {
        $neg = $num < 0;
        
        $num = abs($num);
        
        $str = "";
        
        $alpha = [
            'A', 'B', 'C', 'D', 'E', 
            'F', 'G', 'H', 'I', 'J', 
            'K', 'L', 'M', 'N', 'O', 
            'P', 'Q', 'R', 'S', 'T', 
            'U', 'V', 'W', 'X', 'Y', 
            'Z'];
        
        $len = count($alpha);
        
        if ($num < $len)
        {
            $str = $alpha[$num];
        }
        else
        {
            while($num > 0)
            {
                $mod = ($num % $len);
                $num = (int) ($num / $len);
                
                $str = $alpha[$mod] . $str;
            }
        }
        
        if ($neg)
        {
            $str = "-" . $str;
        }

        return $str;
    }
    
    /**
     * remove the slashs in path
     * @param string $path
     * @param string $side FIRST, LAST
     * @return string
     */
    public static function removePathSlashs($path, $side = '')
    {
        $side = strtoupper($side);
        $path = trim(str_replace('\\', '/', $path));

        if ($side == 'FIRST' || $side == 'START' || empty($side))
        {
            if (substr($path, 0, 1) == "/")
            {
                $path = substr($path, 1, strlen($path));
            }
        }

        if ($side == 'LAST' || $side == 'END' || empty($side))
        {
            if (substr($path, -1) == "/")
            {
                $path = substr($path, 0, strrpos($path, "/"));
            }
        }
        return $path;
    }

    /**
     * Add slashs in path
     * @param string $path
     * @param string $side FIRST, LAST
     * @return string
     */
    public static function addPathSlashs($path, $side = '')
    {
        $side = strtoupper($side);
        $path = trim(str_replace('\\', '/', $path));

        if ($side == 'FIRST' || $side == 'START' || empty($side))
        {
            if (substr($path, 0, 1) != "/")
            {
                $path = "/" . $path;
            }
        }

        if ($side == 'LAST' || $side == 'END' || empty($side))
        {
            if (substr($path, -1) != "/")
            {
                $path .= "/";
            }
        }

        return $path;
    }

    /**
     * Following function convert any type of object to array
     * it can convert xml, json object to array
     * 
     * @param object $obj
     * @return array
     */
    public static function objToArray($obj)
    {
        $arr = array();
        if (gettype($obj) == "object")
        {
            $arr = self::objToArray(get_object_vars($obj));
        } 
        else if (gettype($obj) == "array")
        {
            foreach ($obj as $k => $v)
            {
                $arr[$k] = self::objToArray($v);
            }
        } 
        else
        {
            $arr = $obj;
        }

        return $arr;
    }

    /**
     * sort array on basis char len
     * @param array $arr
     * @return array
     */
    public static function sortArrayOnValueStringLength($arr)
    {
        $temp_list = array_flip($arr);
        $arr = array_keys($temp_list);

        $n = count($arr);
        for ($i = 0; $i < $n; $i++)
        {
            for ($a = $i + 1; $a < $n; $a++)
            {
                if (strlen($arr[$a]) < strlen($arr[$i]))
                {
                    $temp = $arr[$i];
                    $arr[$i] = $arr[$a];
                    $arr[$a] = $temp;
                }
            }
        }

        $ret = array();

        foreach ($arr as $v)
        {
            if (isset($temp_list[$v]))
            {
                $ret[$temp_list[$v]] = $v;
            }
        }
        return $ret;
    }

    /**
     * get rondom string in given char string
     * @param int $length
     * @param String $valid_chars
     * @return string
     */
    function getRandomString($length, $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890")
    {
        $random_string = "";
        $num_valid_chars = strlen($valid_chars);
        for ($i = 0; $i < $length; $i++)
        {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = trim($valid_chars[$random_pick - 1]);

            if (!$random_char)
            {
                $i--;
            } else
            {
                $random_string .= $random_char;
            }
        }
        return $random_string;
    }

    public static function urlencode($data)
    {
        if (is_array($data))
        {
            foreach($data as $k => $v)
            {
                $data[$k] = self::urlencode($v);
            }
            
            return $data;
        }
        else
        {
            return urlencode($data);
        }
    }
    
    public function searilize_array($data)
    {
        if (is_array($data))
        {
            foreach($data as $k => $v)
            {
                $data[$k] = self::urlencode($v);
            }
            
            return $data;
        }
        else
        {
            return utf8_encode(urlencode($data));
        }
    }
}
