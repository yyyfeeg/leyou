<?php

    header("Content-Type:text/html;charset=utf-8");
    error_reporting(0);
    date_default_timezone_set("Asia/chongqing");
    include_once("Uploader.class.php");

    //上传配置
    $config = array(
        "savePath" => "../../lyuploads/frontend_essay/" ,  //存储文件夹
        "maxSize" => 1000 ,                   //允许的文件最大尺寸，单位KB
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )  //允许的文件格式
    );

    $up = new Uploader( "upfile" , $config);
    $type = $_REQUEST['type'];
    $callback=$_GET['callback'];

    $info = $up->getFileInfo();
    
    /**
     * 返回数据
     */
    if($callback) {
        echo '<script>'.$callback.'('.json_encode($info).')</script>';
    } else {
        echo json_encode($info);
    }
?>