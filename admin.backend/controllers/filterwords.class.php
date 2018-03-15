<?php
#============================================
#   FileName: filterwords.class.php
#       Desc: 敏感词库管理
#     Author: Liu
#      Email: 270461709@qq.com
#       Date: 2018.1.23
# LastChange: 
#============================================
class Filterwords extends Controller
{
    /**
     * 构造函数，初始化父类构造函数及检查是否登录
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) {
            showinfo("", "index.php", 1);
        }
    }

    /**
     * 添加敏感词信息
     * @return [type] [description]
     */
    public function add_filter()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('add_filter')) {
            showinfo("你没有权限执行该操作。","",2);
        }
        $act = get_param('act');
        $data['filterwords']   = get_param('filterwords');
        if ($act == 'add'){
            // 检查参数
            $dir = WEBPATH_DIR."lyuploads/filterwords.txt";
            $file_path = iconv('UTF-8','GB2312',$dir);
            if (empty($data['filterwords'])) {
                showinfo("敏感词信息不能为空!", "index.php?module=filterwords&method=add_filter",2);
            } else {
                // 添加数据
                file_put_contents($file_path,'|'.$data['filterwords'], FILE_APPEND);
                $this->admin_log("添加敏感词信息成功");
                showinfo("添加成功!", "index.php?module=filterwords&method=list_filter", 4);
            }
        }
        $this->assign('type','add');
        $this->assign('data',$data);
        $this->display('filterwords.html');
    }
    /**
     * 查看敏感词信息
     * @return [type] [description]
     */
    public function list_filter()
    {
        // 检查权限
        if ($this->isadmin != 1 && !$this->checkright('list_filter')) {
            showinfo("你没有权限执行该操作。","",2);
        }

        $data['filterwords'] = '';
        $dir = WEBPATH_DIR."lyuploads/filterwords.txt";
        $file_path = iconv('UTF-8','GB2312',$dir);
        if(file_exists($file_path)){
            $fp = fopen($file_path,"r");
            $str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
            //$str = str_replace("\r\n","|",$str);
            $data['filterwords'] = $str;
            fclose($fp);
        }

        $this->assign('type','list');
        $this->assign('data',$data);
        $this->display('filterwords.html');
    }
}
?>