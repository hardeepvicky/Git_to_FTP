<?php

$file_csv = $_POST['filename'];

$file_data = CsvUtility::fetchCSV($file_csv);
$done_count = $total_bytes = $upload_bytes = $last_upload_bytes = $last_upload_time = 0;

if ($file_data)
{
    foreach($file_data as $arr)
    {
        $total_bytes += $arr['filesize'];
        if ($arr['is_done'])
        {
            $last_upload_bytes = $arr['filesize'];
            $last_upload_time = $arr['upload_time'];
            $upload_bytes += $arr['filesize'];
            $done_count++;
        }
    }
}

$upload_speed = 0;
if ($last_upload_bytes && $last_upload_time)
{
    $upload_speed = round($last_upload_bytes / $last_upload_time);
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
    "upload_speed" => $upload_speed,
    "commit_list" => $list
));
exit;