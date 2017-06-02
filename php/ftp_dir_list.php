<?php
$path = $_POST['ftp_path'];
header('Cache-Control: no-cache');
try
{
    $ftp = new FtpUtility(FTP_SERVER, FTP_USER, FTP_PASSWORD);

    $list = $ftp->getList($path);
    
    $data = array(
        "dirs" => array(),
        "files" => array()
    );
    foreach ($list as $obj)
    {
        if ($ftp->isDir($path . $obj))
        {
            $data["dirs"][] = array(
                "name" => $obj
            );
        }
        else
        {
            $data["files"][] = array(
                "name" => $obj
            );
        }
    }
    sort($data["dirs"]);
    sort($data["files"]);
    echo json_encode($data);
}
catch (Exception $ex)
{
    header('HTTP/1.1 500 Internal Server Error');
    echo $ex->getMessage();
}

exit;