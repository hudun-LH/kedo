<?php 
if(!defined('ROOT_DIR'))
{
    define('ROOT_DIR', __DIR__.'/../');
}

if(!defined('UPLOAD_DIR'))
{
    define('UPLOAD_DIR', __DIR__ . '/icon/');
}

require_once ROOT_DIR . '/Lib/IconUpload.php';
$upload_handler = new UploadHandler(array(
        'upload_dir'    => UPLOAD_DIR,
        'upload_url'    => '/icon/',
        'user_dirs'        => true,
));