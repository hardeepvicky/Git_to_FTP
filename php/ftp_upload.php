<?php
ini_set("max_execution_time", 60 * 60 * 3);

$file_csv = $_POST['filename'];

$commit_arr = $file_arr = $files = array();

$commit_data = $_POST['commits'];
krsort($commit_data);

foreach($commit_data as $i => $commit)
{
    if ($commit['will_upload'])
    {
        foreach($commit['data']['files'] as $file)
        {
            $file_arr[$file['file']] = array(
                "file" => $file['file'],                
                "will_delete" => $file['is_exist'] == 0 ? 1 : 0,
                "is_done" => 0,
            );
        }
    }
}

CsvUtility::writeCSV($file_csv, $file_arr, true);

header('Cache-Control: no-cache');
try
{
    $ftp = new FtpUtility(FTP_SERVER, FTP_USER, FTP_PASSWORD);
    
    foreach($commit_data as $i => $commit)
    {
        if ($commit['will_upload'])
        {
            foreach($commit['data']['files'] as $file_obj)
            {
                $file = $file_obj['file'];
                if ($file_arr[$file]['is_done'] == 0)
                {
                    if ($file_arr[$file]['will_delete'] == 1)
                    {
                        if ($ftp->delete(FTP_PROJECT_PATH . $file))
                        {
                            $file_arr[$file]["is_done"] = 1;
                            CsvUtility::writeCSV($file_csv, $file_arr, true);
                        }
                        else
                        {
                            throw new Exception("Failed to delete file on FTP Server");
                        }
                    }
                    else
                    {
                        if ($ftp->upload(GIT_PATH . $file, FTP_PROJECT_PATH . $file))
                        {
                            $file_arr[$file]["is_done"] = 1;
                            CsvUtility::writeCSV($file_csv, $file_arr, true);
                        }
                        else
                        {
                            throw new Exception("Failed to upload file on FTP Server");
                        }
                    }
                }
            }
        }
        
        $commit_arr[0] = array(
            "commit" => $commit['data']['commit'],
            "will_upload" => $commit['will_upload'] ? 1 : 0
        );
        
        CsvUtility::writeCSV(SYNC_FILE, $commit_arr, true, ",", "a");
    }
}
catch (Exception $ex)
{
    header('HTTP/1.1 500 Internal Server Error');
    echo $ex->getMessage();
}

echo 1; exit;