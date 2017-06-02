<?php

$file_csv = $_POST['filename'];

$file_data = CsvUtility::fetchCSV($file_csv);
$done_count = $total_bytes = $upload_bytes =  0;

if ($file_data)
{
    foreach($file_data as $arr)
    {
        $total_bytes += $arr['filesize'];
        if ($arr['is_done'])
        {
            $upload_bytes += $arr['filesize'];
            $done_count++;
        }
    }
}

$data = CsvUtility::fetchCSV(SYNC_FILE);
$list = array();
if ($data)
{
    foreach($data as $arr)
    {
        $list[] = $arr['commit'];
    }
}

header('Cache-Control: no-cache');
echo json_encode(array(
    "total_count" => count($file_data),
    "done_count" => $done_count,
    "total_bytes" => $total_bytes,
    "upload_bytes" => $upload_bytes,
    "commit_list" => $list
));
exit;