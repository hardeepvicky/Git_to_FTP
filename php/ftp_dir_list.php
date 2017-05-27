<?php
$path = $_POST['ftp_path'];
header('Cache-Control: no-cache');
try
{
    $ftp = new FtpUtility(FTP_SERVER, FTP_USER, FTP_PASSWORD);

    $list = $ftp->getList($path);
    
    $data = array();
    foreach ($list as $obj)
    {
        $data[] = array(
            "obj" => $obj,
            "is_dir" => $ftp->isDir($path . $obj) ? 1 : 0
        );
    }

    echo json_encode($data);
}
catch (Exception $ex)
{
    header('HTTP/1.1 500 Internal Server Error');
    echo $ex->getMessage();
}

exit;