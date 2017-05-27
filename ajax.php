<?php
require_once './php/include/functions.php';
require_once './config.php';
require_once './php/include/DateUtility.php';
require_once './php/include/CsvUtility.php';
require_once './php/include/Util.php';
require_once './php/include/FileUtility.php';
require_once './php/include/FtpUtility.php';

$load_file = isset($_GET['load_file']) ? $_GET['load_file'] : "index";
$load_file .= ".php";

require_once "php/$load_file";
include_once "html/$load_file";