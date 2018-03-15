<?php
        $fsize = $_POST['size'];
        $findex =$_POST['indexCount'];
        $ftotal =$_POST['totalCount'];
        $ftype = $_POST['type'];
        $fdata = $_FILES['file'];
        $fname = mb_convert_encoding($_POST['name'],"gbk","utf-8");
        $type = end(explode(".",$fname));
        $truename = mb_convert_encoding($_POST['trueName'],"gbk","utf-8");

        $dir = '../lyuploads/' . $type ; //PACKAGE_URL为绝对路径，/var/www/....
        $fname = md5($fname); 
        $save = $dir."/".$fname.".".$type;
        if(!is_dir($dir))
        {
            mkdir($dir);
            chmod($dir,0777);//给文件夹以写的权限
        }
        //读取临时文件内容
        $temp = fopen($fdata["tmp_name"],"r+");//打开
        $filedata = fread($temp,filesize($fdata["tmp_name"]));//读取文件

        //将分段内容存放到新建的临时文件里面
        if(file_exists($dir."/".$findex.".tmp")) unlink($dir."/".$findex.".tmp");//是否存在当前的临时片名
        $tempFile = fopen($dir."/".$findex.".tmp","w+");//打开

        fwrite($tempFile,$filedata);//写入 
        fclose($tempFile);//关闭

        fclose($temp);
    
        if($findex+1==$ftotal)
        {
            if(file_exists($save)) @unlink($save);
            //循环读取临时文件并将其合并置入新文件里面
            for($i=0;$i<$ftotal;$i++)
            {
                $readData = fopen($dir."/".$i.".tmp","r+");
                $writeData = fread($readData,filesize($dir."/".$i.".tmp"));//读取文件

                $newFile = fopen($save,"a+");
                fwrite($newFile,$writeData);
                
                fclose($newFile);
                
                fclose($readData);

                $resu = @unlink($dir."/".$i.".tmp"); 
            }          
            $fnewszie = filesize($dir."/".$fname.".".$type);
            if($fsize==$fnewszie)
            {
                $test = array("msg"=>"success");
            }else{
                $test = array("msg"=>"fail");
            } 
            
            $res = array("res"=>"success","test"=>$test,"fsize"=>$fsize,"newsize"=>$fnewszie,"url"=>mb_convert_encoding($truename."-".$fsize."/".$fname,'utf-8','gbk'),"package_url"=>$dir.'/'.$fname);
    
            echo json_encode($res);
        }
?>
