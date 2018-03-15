<?PHP
include_once(WEBPATH_DIR."libs/Smarty.class.php");
class game_Smarty extends Smarty {
	function game_Smarty() {
		$this->template_dir  = WEBPATH_DIR.'templates/';//模板地址
		$this->themes_dir    = WEBPATH_DIR_INC.'templates/';//图片以及JS等文件的修正地址
		$this->compile_dir   = WEBPATH_DIR.'templates_c/';
		$this->config_dir    = WEBPATH_DIR.'configs/';
		$this->caching       = false;
		$this->force_compile = true;
	}

}



?>
