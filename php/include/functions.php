<?php

function debug($data)
{
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    
    echo "<pre>";
    echo "<b>" . $caller["file"] . " : " . $caller["line"] . "</b><br/>";
    print_r($data);
    echo "</pre>";
}

function url($file, $args = array(), $base_file = "index")
{
    $args['load_file'] = $file;

    $query_str = "";
    foreach($args as $arg => $v)
    {
        $query_str .= "$arg=$v&";
    }

    $query_str = substr($query_str, 0, -1);

    return BASE_URL . "$base_file.php?$query_str";
}

/**
 * return array to where sql string
 * @param type $conditions
 * @return string
 */
function get_where($conditions)
{
    $where = array();
    
    $raw_where = '';
    
    foreach($conditions as $operator => $data)
    {
        foreach($data as $arr)
        {
            if (isset($arr["field"]) && isset($arr["value"]))
            {
                $arr["op"] = isset($arr["op"]) ? $arr["op"] : "=";
                
                $where[] = $arr["field"] . " " . $arr["op"] . " '" . $arr["value"] . "'";
            }
            else
            {
                $where[] = get_where($arr);
            }            
        }
        
        $raw_where .= "(" . implode(" $operator ",  $where) . ")";
    }
    
    return $raw_where;
}

function str_contain($str, $needle, $start = false, $end = false)
{
    $str = strtolower(trim($str));
    $needle = strtolower(trim($needle));
    
    if ($start !== false)
    {
        $str = substr($str, $start);
    }
    
    if ($end !== false)
    {
        $str = substr($str, 0, $end);
    }
    
    return strpos($str, $needle) !== false;
}

function file_list_to_tree_list($list)
{
    $tree = array();
    
    foreach($list as $file)
    {
        $path = trim(pathinfo($file, PATHINFO_DIRNAME));
        
        $folders = explode("/", $path);
        
        $structure = array();
        for($i = count($folders) - 1; $i >= -1;  $i--)
        {
            if ($i >= 0)
            {
                if ($folders[$i])
                {
                    $structure[$folders[$i]] = $structure;
                }
            }
            else
            {
                $structure[$folders[$i + 1]][] = pathinfo($file, PATHINFO_BASENAME);
            }
        }
        
        $tree = array_merge_recursive($tree, $structure);
    }
    
    return $tree;
}