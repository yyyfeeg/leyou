<?php
#============================================
# 	FileName: mysmarty.class.php
# 		Desc: smarty配置类文件
# 	  Author: Tang
# 	   Email: 799345505@qq.com
# 		Date: 2015.11.03
# LastChange: 
#============================================

include_once(WEBPATH_DIR.'libs/Smarty.class.php'); //包含smarty主类文件

class MySmarty extends Smarty
{
	function __construct()
	{
		$this->template_dir = WEBPATH_DIR.'templates/'; //设置模板目录
		$this->themes_dir   = WEBPATH_DIR_INC.'templates/'; //图片以及JS等文件的修正地址
		$this->compile_dir  = WEBPATH_DIR.'templates_c/'; //设置编译目录
		$this->caching  	= false; //是否开启缓存
		$this->force_compile= true; //强迫编译
	}
}
?>