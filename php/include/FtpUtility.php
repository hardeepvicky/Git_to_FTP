<?php

class FtpUtility
{
    private $server, $user, $pass, $conn;
    
    public function __construct($server, $user, $pass, $port = 21)
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        
        $this->conn = ftp_connect($this->server, $port, 300);
        
        if (!$this->conn)
        {
            throw new Exception("could not connect $server");
        }
        
        if ( !ftp_login($this->conn, $user, $pass))
        {
            throw new Exception("could not login $server with user : $user and password : $pass");
        }
        
        ftp_pasv($this->conn, true);
    }
    
    public function getList($path)
    {
        if (!$this->isDir($path))
        {
            return array();
        }
        
        $list = ftp_nlist($this->conn, $path);
        
        return $list;
    }
    
    public function isDir($path)
    {
        $current = ftp_pwd($this->conn);
        $path = trim(trim($path), "/");
        $path = str_replace("\\", "/", $path);
        $paths = explode("/", $path);
        
        foreach($paths as $path)
        {
            if (!@ftp_chdir($this->conn, $path))
            {
                ftp_chdir($this->conn, $current);
                return false;
            }
        }
        
        ftp_chdir($this->conn, $current);
        return true;
    }
    
    public function createFolder($path)
    {
        $current = ftp_pwd($this->conn);
        $path = trim(trim($path), "/");
        $path = str_replace("\\", "/", $path);
        $paths = explode("/", $path);
        
        foreach($paths as $k => $path)
        {
            $path = trim($path);
            if (empty($path))
            {
                unset($paths[$k]);
                continue;
            }
            
            if (!@ftp_chdir($this->conn, $path))
            {
                if (!ftp_mkdir($this->conn, $path))
                {
                    ftp_chdir($this->conn, $current);
                    return false;
                }
            }
        }
        
        ftp_chdir($this->conn, $current);
        if (empty($paths))
        {
            return false;
        }
        
        if (count($paths) == 1)
        {
            return reset($paths) . "/";
        }
        
        return "/" . implode("/", $paths) . "/";
    }
    
    public function upload($local_file, $server_file)
    {
        $server_path = pathinfo($server_file, PATHINFO_DIRNAME);
        
        $server_path = $this->createFolder($server_path);
        if ($server_path === false)
        {
            throw new Exception("Could not create folder $server_path at FTP Server");
        }
        
        $server_file = pathinfo($server_file, PATHINFO_BASENAME);
        if ( !ftp_put($this->conn, $server_path . $server_file, $local_file, FTP_BINARY))
        {
            return FALSE;
        }
        
        return true;
    }
    
    public function delete($server_file)
    {
        $server_path = pathinfo($server_file, PATHINFO_DIRNAME) . "/";
        
        if (!$this->isDir($server_path))
        {
            return true; // folder not found
        }
        
        $server_file = pathinfo($server_file, PATHINFO_BASENAME);
        
        $size = ftp_size($this->conn, $server_path . $server_file);
        
        if ($size == -1)
        {
            return true; // file not exist
        }
        
        if ( !ftp_delete($this->conn, $server_path . $server_file))
        {
            return FALSE;
        }
        
        return true;
    }
    
    public function isFile($server_file)
    {
        $server_path = pathinfo($server_file, PATHINFO_DIRNAME);
        
        $server_path = $this->isDir($server_path);
        if ($server_path == false)
        {
            return false;
        }
        
        $server_file = pathinfo($server_file, PATHINFO_BASENAME);
        
        $size = ftp_size($this->conn, $server_path . $server_file);
        
        return $size == -1 ? false : true;
    }
}
