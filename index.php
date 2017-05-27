<?php
require_once './php/include/functions.php';
require_once './config.php';
require_once './php/include/DateUtility.php';
require_once './php/include/CsvUtility.php';
require_once './php/include/FileUtility.php';
require_once './php/include/GitUtility.php';
require_once './php/include/Session.php';

if (!FileUtility::createFolder(BASE_PATH))
{
	die("failed to create path " . BASE_PATH);
}

$load_file = isset($_GET['load_file']) ? $_GET['load_file'] : "index";
$load_file .= ".php";

require_once "php/$load_file";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Git to FTP - <?= DEVELOPER ?></title>

        <link rel="stylesheet" href="html/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="html/bootstrap/css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="html/bootstrap-datatable/jquery.dataTables.min.css"/>
        <link rel="stylesheet" href="html/bootstrap-dialog/bootstrap-dialog.min.css"/>
        <link rel="stylesheet" href="html/tf-loader/tf-loader.css"/>
        <link rel="stylesheet" href="html/css/theme.css"/>
        
        <script src="html/js/jquery-3.1.1.js" type="text/javascript"></script>
        <script src="html/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="html/bootstrap-datatable/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="html/bootstrap-datatable/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <script src="html/bootstrap-dialog/bootstrap-dialog.min.js" type="text/javascript"></script>
        <script src="html/sortable/jquery.sortable.min.js" type="text/javascript"></script>
        <script src="html/tf-loader/tf-loader.js" type="text/javascript"></script>
        
        <script src="html/js/jquery-extend.js" type="text/javascript"></script>
        <script src="html/js/default.js" type="text/javascript"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= BASE_URL; ?>">
                        Git to FTP - <?= DEVELOPER ?>
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container theme-showcase" role="main">
            
            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success" role="alert">
                    <strong>Done!</strong> <?= Session::readFlash('success'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (Session::hasFlash('warning')): ?>
                <div class="alert alert-warning" role="alert">
                    <strong>Warning!</strong> <?= Session::readFlash('warning'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (Session::hasFlash('info')): ?>
                <div class="alert alert-warning" role="alert">
                    <strong>Info!</strong> <?= Session::readFlash('info'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (Session::hasFlash('failure')): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Failure!</strong> <?= Session::readFlash('failure'); ?>
                </div>
            <?php endif; ?>
            
            <?php include_once "html/$load_file"; ?>
        </div>
    </body>
</html>
