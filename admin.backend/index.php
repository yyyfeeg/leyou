<?PHP
	include_once("./config.inc.php");
	$module = (get_param('module'))? get_param('module'):"login";
  $method = (get_param('method'))? get_param('method'):"index";
  $types  = get_param('types')?get_param('types'):"";
    //unset($_GET['module']);
    //unset($_GET['method']);

	if(empty($module) || empty($method)){
    	$msg = "Parameter Wrong!";
		exit($msg);
	}
	$obj = load_controller($module,$types);
   	if(!method_exists($obj, $method)){
       	$msg = "Methord ($method) Is Not Found!";
		exit($msg);
   	}

   	call_user_func_array(array($obj, $method),array());
?>
