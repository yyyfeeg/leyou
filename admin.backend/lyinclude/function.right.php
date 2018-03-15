<?php
/**
 * 获取菜单树
 * @$conn 数据库连接
 * @$group_id 管理员所在组id，根据管理员拥有的菜单列出来,此时$all一定为false
 * @$all true,不区别管理员全部列出菜单
 * return 数组
 * */
$DB = new DB_ZDE();
function get_tree($conn,$group_id,$all=FALSE,$type=TRUE,$rights=''){
	if($all){
		$and   =  "";
	}else{
		if($type){
			$right  =  split_filed ( $conn, 'admin_group', 'ag_rightid', 'sysid=' . $group_id );
			$and  =  ' and ar_rightid in(' . addquote ( ',', $right, '"' ) . ') ';
		}else{
			$and  =  ' and ar_rightid in(' . addquote ( ',', $rights, '"' ) . ') ';
		}
		
	}
	$sql   =   "select sysid as menuid,ar_rightid,ar_rightname as menuname,ar_parentid as pid,ar_url as url
				 from ".get_table("admin_right")." where ar_ismenu=1 and ar_islock=2 $and order by ar_order desc";
	$datas =   $conn->getAll($conn->query($sql));
	if(!empty($datas)){
		//$_SESSION['myrights'] = $right;		//将权限保存到session中
		return getTrees($datas);
	}
	return false;
}

/** 功能:获取一个或多个权限组的权限
 * $groupid 权限组id,为空时返回全部权限组
 * $handle true则处理数组
 * $conn 数据库连接,添加纯属了为了兼容性考虑
 * 返回值:二维数组，按权限组返回关联权限
 * 关联式返回值:$array[group][]获取权限组,$array[$groupid.'right'][]获取权限组内的权限
 * array(
 * 'group'=>array(
 * "groupid"=>"groupname",
 * ···
 * ),
 * $groupid.'right'=>array(
 * "rightid"="rightname",
 * ···
 * )
 * ···
 * )
 * 非关联式返回值:$array[group]获取权限组,$array[$groupid.'right'][][]获取权限组内的权限
 * array(
 * 'group'=>array(
 * 0=>array(
 * 0=>"groupid",
 * 1=>"groupname",
 * ),
 * ···
 * ),
 * $groupid.'right'=>array(
 * 0=>array(
 * 0=>"rightid",
 * 1="rightname",
 * ),
 * ···
 * ),
 * ···
 * )
 * */
function get_groupright($groupid = '', $conn = '', $handle = FALSE) {
	if ($groupid) {
		$condition = ' and sysid=' . $groupid . ' limit 1';
	}
	$groupsql = 'select * from ' . get_table ( "admin_group" ) . ' where 1 ' . $condition;

	if (empty ( $conn )) {
		$conn = $GLOBALS [conn];
	}
	$groupquery = $conn->Query ( $groupsql );
	$groupArra = $conn->getAll ( $groupquery );
	$array = array ();
	if (! $handle) {
		$i = 0;
		$j = 0;
	}
	foreach ( $groupArra as $val ) {
		$rightsql = 'select * from ' . get_table ( "admin_right" ) . ' where ar_rightid in(' . addquote ( ',', $val [ag_rightid], '"' ) . ')';
		$rightquery = $conn->Query ( $rightsql );
		$rightArra = $conn->getAll ( $rightquery );
		foreach ( $rightArra as $value ) {
			if ($handle) {
				$array [group] [$val [sysid]] = $val [ag_groupname];
				$array [$val [sysid] . 'right'] [$value [ar_rightid]] = $value [ar_rightname];
			} else {
				$array [group] [$i] [groupid] = $val [sysid];
				$array [group] [$i] [grouname] = $val [ag_groupname];
				$array [$val [sysid] . 'right'] [$j] [rightid] = $value [ar_rightid];
				$array [$val [sysid] . 'right'] [$j] [rightname] = $value [ar_rightname];
				$j ++;
			}
		}
		if (! $handle)
			$i ++;
	}
	return $array;
}


/**
 * 获取联运商信息
 * @param type $db 
 */
function get_trans($db){
        $trans = array();
        $info  = array();
        $trans= get_info($db,'admin_trans',array('sysid','at_name'),'','',true);
        foreach($trans as $k=>$v)
        {
            $info[$v['sysid']] = $v['at_name'];
        }
        return $info;
}


/**
 * 返回服务器名
 */
function get_servers($conn,$server_id="",$wherearr="",$flag=1){//获取服务器区名称 
        
	if(!empty($server_id)){
		$where = " gs_sid=$server_id";
	}else{
		$where ="1=1";
	}
    if(is_array($wherearr)){
        foreach($wherearr as $key=>$value){
            if(!empty($value)){
                $where .= " and $key = $value";
            }
        }
    }
	$sql = "select sysid,gs_gid,gs_sid,gs_sname from dcenter_base.`gsys_game_server` where {$where}";
	$result = $conn->query($sql);
	if($result){
		while($rs=$conn->FetchArray($result)){
			//返回名称和id
			if ($flag==1) {
				$servers[$rs['gs_gid']][$rs['gs_sid']] = $rs['gs_sname'];
			} else {
				$servers[$rs['sysid']] = $rs['gs_sname'];
			}
		}
		//$servers = $result['gs_sname']."[".$result['gs_sid']."]";
		return $servers;
	}else{
		return false;
	}
}

/*
 * 返回sysid和对应名称，供产品阶段、产品类型、风格题材、公司名称、语言、区域等选项获取，仅仅使用测评数据库相关表
 * 
 * @$tablename 表名称
 * @$colname 名字所对应的字段名称
 * @$type 为0时返回数组，否则返回字符串
 * @$selectID 仅当type不等于0时   返回select选项时  选项是否选中
 * return 数组
*/
function get_options($tablename,$colname,$type=0,$selectID=0,$where=""){
	$sql="SELECT `sysid` as id , `$colname` as name FROM `$tablename` where 1=1 $where";
	$str='';
	get_evaluation_conn();
	$rst=$GLOBALS["games"]->getAll($GLOBALS["games"]->query($sql));
	if (!empty($rst)){
		$rst=my_sort2($rst, "name");//根据汉字编码排序
	}
	if (empty($type)) {
		$data=array();
		if (!empty($rst)) {
			foreach ( $rst as $row){
				$data[$row['id']]=$row['name'];
			}
		}
		return $data;
	}
	if (!empty($rst)) {
		foreach ($rst as $each) {
			$selectstr="";
			if ($each['id']==$selectID) {
				$selectstr='selected="selected"';
			}
			$str.="<option value='".$each['id']."' ".$selectstr.">".$each['name']."</option>";
		}
	}
	return $str;
}

/**
 * 获取用户列表
 * @param  integer $type     [description]
 * @param  integer $selectID [description]
 * @param  string  $where    [description]
 * @return [type]            [description]
 */
function get_users($type=0,$selectID=0,$where=""){
	$sql="SELECT `sysid` as id , `a_truename` as name FROM `gsys_admin` WHERE 1 $where";
	$str='';
	get_base_conn();
	$rst=$GLOBALS["conn"]->getAll($GLOBALS["conn"]->query($sql));
	if (!empty($rst)){
		$rst=my_sort2($rst, "name");//根据汉字编码排序
	}
	if (empty($type)) {
		$data=array();
		if (!empty($rst)) {
			foreach ( $rst as $row){
				$data[$row['id']]=$row['name'];
			}
		}
		return $data;
	}
	if (!empty($rst)) {
		foreach ($rst as $each) {
			$selectstr="";
			if ($each['id']==$selectID) {
				$selectstr='selected="selected"';
			}
			$str.="<option value='".$each['id']."' ".$selectstr.">".$each['name']."</option>";;
		}
	}
	return $str;
}


function get_group($db,$gid=''){
    if(!empty($gid)){
        $and = ' and sysid = '.$gid;
    }
    $sql = 'select sysid,ag_groupname from '.get_table('admin_group').'where 1'.$and;
    $query = $db->Query($sql);
    while ($row = $db->FetchArray($query)) {
        $group_list[$row['sysid']] = $row['ag_groupname'];
    }
    return $group_list;
}

//管理员名字
function get_admin_name($conn,$aid = ''){
    $where = ' where 1=1';
    if(!empty($aid)){
        $where .= ' sysid = '.$aid;
    }
    $sql = 'select sysid,a_truename from '.get_table('admin').$where;
    $query = $conn->Query($sql);
    while ($row = $conn->FetchArray($query)) {
        $admin_list[$row['sysid']] = $row['a_truename'];
    }
    return $admin_list;
}

//获取游戏
function get_game($conn,$gid = ''){
    $where = ' where 1=1';
    if(!empty($gid)){
        $where .= ' and sysid = '.$gid;
    }else{
        $where .= $_SESSION["isadmin"]==1?"":' and sysid in ('.$_SESSION['myad_permission'].')';
    }
	$sql = 'select sysid,gi_gname from '.get_table('game_info').$where;
    $query = $conn->Query($sql);
    while ($row = $conn->FetchArray($query)) {
        $game_list[$row['sysid']] = $row['gi_gname'];
    }
    return $game_list;
}


//返回nav相关
function get_nav($conn){
    $ltime = $conn->getOne($conn->query("select a_lastdate,a_email from ".get_table("admin")." where sysid=".$_SESSION["my_admin_id"]));
    $ltime['admin_name'] = $_SESSION["my_admin_id"];
    $ltime['a_lastdate'] = date('m/d H:i',$ltime['a_lastdate']);
    return $ltime;
}

//获取团队
function get_team($conn,$tid = ''){
    $where = ' where 1=1';
    if(!empty($tid)){
        $where .= ' and sysid = '.$tid;
    }
	$sql = 'select sysid,ti_teamname from '.get_table('team_info').$where;
    $query = $conn->Query($sql);
    while ($row = $conn->FetchArray($query)) {
        $team_list[$row['sysid']] = $row['ti_teamname'];
    }
    return $team_list;
}

//获取注册渠道
function get_transport($conn,$tid = ''){
    $where = ' where 1=1';
    if(!empty($tid)){
        $where .= ' and sysid = '.$tid;
    }else{
        $where .= $_SESSION["isadmin"]==1?"":' and gc_gid in ('.$_SESSION['myad_permission'].')';
    }
	$sql = 'select sysid,gc_cname from '.get_table('game_channels').$where;
    $query = $conn->Query($sql);
    while ($row = $conn->FetchArray($query)) {
        $transport_list[$row['sysid']] = $row['gc_cname'];
    }
    return $transport_list;
}

//获取所有渠道选项（仅选择大渠道情况下）
function get_adinfo($conn,$tid){
	$where = ' where 1=1 ';
	if(!empty($tid)){
        $where .= ' and gp_transport = '.$tid;
    }else{
    	$where .= $_SESSION["isadmin"]==1?"":' and sysid in ('.$_SESSION['myad_permission2'].')';
    }

    $sql   = "select group_concat(sysid) sysid from ".get_table("game_partad")." $where and gp_aid=0";
    $query = $conn->Query($sql);
 	return $conn->getOne($query);
}


//获取广告渠道列表数据
/*
	$flage:所有主渠道或者子渠道
	$id:指定id
	$tid:指定机型ios/安卓 类型
	$flage:指定主广告
	$isshow:是否显示ID和大渠道
*/
function ad_get($conn,$flage='',$id = '',$tid="",$mid="",$isshow=""){
    $where = ' where 1=1 ';
    if(!empty($id)){
        $where .= ' and sysid = '.$id;
    }else{
    	$where .= $_SESSION["isadmin"]==1?"":' and sysid in ('.$_SESSION['myad_permission2'].')';
    }
    $tid ?$where 		.= " and gp_transport=".$tid : '';
    $mid ?$where 		.= " and gp_aid=".$mid : '';
    $flage=='a'?$where .= ' and gp_aid = 0':'';
    $flage=='b'?$where .= ' and gp_aid != 0':'';
    //查询出所有的主站
	$sql = "select sysid,gp_name,gp_transport from ".get_table('game_partad').$where;
	$query = $conn->Query($sql);
    while ($row = $conn->FetchArray($query)) {
    	switch ($row['gp_transport']) {
    		case '1':
    			$transport = "[安卓]";
    			break;
    		case '2':
    			$transport = "[ios]";
    			break;
			case '3':
				$transport = "[ios越狱]";
				break;
    		default:
    			$transport = "";
    			break;
    	}
    	if(!empty($isshow)){
    		$ad_list[$row['sysid']] = $row['gp_name'];
    	}else{
        	$ad_list[$row['sysid']] = $row['gp_name']."[".$row['sysid']."]".$transport;
    	}
    }
    return $ad_list;
}

/**
 * 格式化无限级分类
 * @param  [type]  $dataArr   数组
 * @param  integer $pid       父栏目ID
 * @param  integer $level     层级深度
 * @param  string  $partition 分隔符
 * @return [type]             二维数组
 */
function get_rubric_list($dataArr, $pid=0, $level=0, $partition='|——')
{
	$tmp = array();
	if (is_array($dataArr)) {
		$str = str_repeat("&nbsp;&nbsp;", $level*3);
		foreach ($dataArr as $value) {
			if ($value['fr_fid'] == $pid) {
				$str1 = empty($value['fr_fid']) ? '':$partition;
				$tmp[] = array(
					'sysid' => $value['sysid'],
					'fr_fid' => $value['fr_fid'],
					'fr_name' => $str.$str1.$value['fr_name'],
					'fr_order' => $value['fr_order'],
					'level' => $level
					);
				$tmp = array_merge($tmp, get_rubric_list($dataArr,$value['sysid'],$level+1));
			}
		}
		return $tmp;
	}
	return false;
}

/**
 * 获取无限分类栏目列表
 * @return [type] [description]
 */
function get_rubric($conn, $pid=0, $level=0, $partition='|——')
{
	$sql = "select * from ".get_table('frontend_rubric')." order by fr_order asc,sysid asc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[] = $rows;
	}
	return get_rubric_list($tmp,$pid,$level,$partition);
}

/**
 * 获取图片分类数组
 * @param  [type] $conn 数据库链接资源
 * @return [type]       一维数组
 */
function get_phototype($conn)
{
	$tmp = array();
	$sql = "select sysid,fp_name from ".get_table('frontend_phototype')." order by sysid asc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[$rows['sysid']] = $rows['fp_name'];
	}
	return $tmp;
}

/**
 * 获取栏目数组
 * @param  [type] $conn 数据库链接资源
 * @return [type]       [description]
 */
function get_rubric_keyvalue($conn,$type=1)
{
	$tmp = array();
	$sql = "select sysid,fr_name,fr_dir,fr_template from ".get_table('frontend_rubric')." order by sysid asc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		// 获取sysid和栏目名称数组
		if ($type == 1) {
			$tmp[$rows['sysid']] = $rows['fr_name'];
		}

		// 获取sysid和栏目文件保存目录数组
		if ($type == 2) {
			$tmp[$rows['sysid']] = $rows['fr_dir'];
		}

		// 获取sysid和栏目模板名称数组
		if ($type == 3) {
			$tmp[$rows['sysid']] = $rows['fr_template'];
		}
		
	}
	return $tmp;
}


/**
 * 获取上级栏目
 * @param  [type]  $dataArr 所有栏目数组
 * @param  integer $cid     当前栏目ID
 * @return [type]           [description]
 */
function get_rubric_fid($dataArr, $cid=0)
{
	$tmp = array();
	if (is_array($dataArr)) {
		foreach ($dataArr as $value) {
			if ($value['sysid'] == $cid) {
				$tmp[] = array(
					'sysid' => $value['sysid'],
					'fr_dir' => $value['fr_dir']
					);
				$tmp = array_merge($tmp, get_rubric_fid($dataArr,$value['fr_fid']));
			}
		}
		return array_reverse($tmp);
	}
	return false;
}

/**
 * 获取所有栏目列表
 * @return [type] [description]
 */
function get_rubric2($conn)
{
	$sql = "select * from ".get_table('frontend_rubric')." order by fr_order asc,sysid asc";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$tmp[] = $rows;
	}
	return $tmp;
}

/**
 * 获取一个栏目文件保存目录
 * @param  [type]  $conn [description]
 * @param  integer $cid  [description]
 * @return [type]        [description]
 */
function get_rubric_dir($conn,$cid=0)
{
	$dir = '';
	$rubric_arr = get_rubric2($conn);
	$rubric = get_rubric_fid($rubric_arr,$cid);
	foreach ($rubric as $value) {
		$dir .= $value['fr_dir'];
	}
	return $dir;
}

/**
 * 返回礼包类型数组
 * @param  [type]  $conn     数据库资源
 * @param  integer $platform 是否平台展示 1是 2否
 * @return [type]            [description]
 */
function get_ctype($conn,$platform=0)
{
	$where = '';
	if (!empty($platform)) {
		$where .= " and gst_platform = $platform";
	}
	$sql = "select sysid,gst_name from gsys_spree_type where gst_open=1 $where";
	$query = $conn->Query($sql);
	while ($rows = $conn->FetchArray($query)) {
		$stype_list[$rows['sysid']] = $rows['gst_name'];
	}
	return $stype_list;
}

?>