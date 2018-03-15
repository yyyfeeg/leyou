<?php

#=============================================================================
#     FileName: permigroup.class.php
#    CopyRight: Teamtop Information Technology Co., Ltd.
#         Desc: 权限组类
#       Author: jericho
#        Email: jericho [AT] 3737.com
#     HomePage: http://www.hlwy.com/
#      Version: 0.0.1
#   LastChange: 2013-06-04
#      History:
#=============================================================================

class Permigroup extends Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->checklogin()) 
		{
			showinfo("","index.php",1);
		}
    }

    /**
	 * access public
	 *
	 * 添加权限组
	 */
    public function add_group()
    {
        if($this->isadmin!=1 && !$this->checkright("addgroup")){
            showinfo("仅超级管理员才能够执行该操作。","",2);
        }
		$act   = get_param("act");
		if($act == "insert")
		{
			$rights    = get_array("rightid");
			$groupname = get_param("groupname");
			if(empty($groupname))
			{
                                showinfo("权限组不能为空","",3);
			}
			if(empty($rights))
			{
                                showinfo("权限不能为空","",3);
			}
                        
                        //判断是否非法增加其他权限
                        $diff = @array_diff(get_array("rightid",',',true),$this->myrights);
                        if(empty($diff)||$this->isadmin==1){
                            $data['ag_fid']   = $this->myad_groupid;
                            $data['ag_rightid']   = get_array("rightid",',',true);//转换成字符串，并且消除重复
                            $data['ag_groupname'] = get_param("groupname");
                            $data['ag_jmenu']     = get_json_encode(get_tree($this->db,'',false,false,$rights)); 
                            $data['ag_addtime']   = THIS_DATETIME;
                            $data['ag_userid']    = $this->my_admin_id;
                            $data['ag_content']   = get_param("groupcont");
                            $where                = array("ag_groupname"=>$data['ag_groupname']);
                            $isexits              = exist_check($this->db, "admin_group", $where);       //判断是否已经存在此权限编号
                            if($isexits){
                                    $this->admin_log("添加新权限组".$data['ag_groupname']."失败，原因：权限组已存在");
                                    showinfo("该权限组已存在!","",3);
                            }
                            $rst = add_record($this->db, 'admin_group', $data);
                            if (0 < $rst['rows']){
                                    $message = '成功添加权限组:'.$data['ag_groupname'];
                                    $this->admin_log($message);
                                    showinfo("添加权限组成功","index.php?module=permigroup&method=group_list",4);
                            }else{
                                    $message = '添加权限组,失败:数据更新错误';
                                    $this->admin_log($message);
                                    showinfo("添加权限组失败","index.php?module=permigroup&method=add_group",4);
                            }
                        }else{
                            $message = '添加权限组,失败:非法传递参数';
                            $this->admin_log($message);
                            showinfo("添加权限组失败，请按规定操作","index.php?module=permigroup&method=add_group",4);
                        }
		}
        $and     =  ($this->isadmin!=1)?" and ar_rightid in(".addquote(',',$this->myrights).")":"";   //非超级管理员只显示当前组权限
        $sql   =   "select sysid as menuid,ar_rightid,ar_rightname as menuname,ar_parentid as pid,ar_url as url,ar_ismenu
                         from ".get_table("admin_right")." where  ar_islock=2 $and order by sysid";
        $query = $this->db->query($sql);
	    $result = $this->db->getAll($query);
        $menu  = getTrees($result);
        foreach ($menu['menus'] as $gpk => $gpv) {
            if (isset($gpv['menus']) && count($gpv['menus']) > 0) {
                foreach ($gpv['menus'] as $pk => $pv) {
                    if(is_array($cv['ar_rightid'])){
                        $ccheckd = (in_array($cv['ar_rightid'], $myright)) ? 'checked' : '';
                    }
                    $list[$pv['pid']]['p'][$pv['menuid']] = "<label><input type='checkbox' " . $pcheckd . " onclick='gets(".$pv['menuid'].");getp(".$gpv['menuid'].");' name='rightid[]' value='" . $pv['ar_rightid'] . "' class='" . $gpv['menuid'] . "oo box " . $gpv['menuid'] . "c " . $gpv['menuid'] . "_" . $pv['menuid'] . "pp' id='" . $pv['menuid'] . "c' alt='1'><span class='text' for='" . $pv['ar_rightid'] . "c'>" . $pv['menuname'] . "</span></label>";

                    if (isset($pv['child']) && count($pv['child']) > 0) {
                        $list[$pv['pid']]['p'][$pv['menuid']] .= "<label class='box'>─▶</label>";
                        foreach ($pv['child'] as $ck => $cv) {
                            if(is_array($cv['ar_rightid'])){
                                $ccheckd = (in_array($cv['ar_rightid'], $myright)) ? 'checked' : '';
                            }
                            
                            if($cv['ar_ismenu']==2){
                               $color = "style='color:red'";
                            }else{
                               $color = "";
                            }
                            $list[$pv['pid']]['p'][$pv['menuid']] .= "<label><input type='checkbox' " . $ccheckd . " onclick='getpp(" . $gpv['menuid'] . "," . $pv['menuid'] . ")' name='rightid[]' value='" . $cv['ar_rightid'] . "' class='" . $pv['menuid'] . "o box " . $gpv['menuid'] . "c' id='" . $cv['ar_rightid'] . "c' alt='1'><span class='text' $color for='" . $cv['ar_rightid'] . "c'>" . $cv['menuname'] . "</span></label>";
                        }
                        $list[$pv['pid']]['p'][$pv['menuid']] .='<br/>';
                    }
                }

                $lsit[$pv['pid']]['p'][$pv['menuid']] ='<label><input type="checkbox" value="2" name="terrace[]"><span class="text">123</span></label>';

                $list[$gpv['menuid']]['gp'] = "<label><input type='checkbox' " . $gcheckd . " onclick='get(" . $gpv['menuid'] . ")' value='" . $gpv['ar_rightid'] . "'  id='" . $gpv['menuid'] . "p' class='p " . $gpv['menuid'] . "p " . $gpv['menuid'] . "pp' name='rightid[]' alt='1' /><span class='text'>".$gpv['menuname'].'</span></label>';
            }
        }
        
        $this->assign("type","addgroup");
		$this->assign('list',$list);
        $this->display('permisson.html');
    }

    
	/**
	 *
	 *
	 * 权限组列表
	 */
    public function group_list()
    {
	if($this->isadmin!=1 && !$this->checkright("group_list")){
            showinfo("你没有权限执行该操作。","",2);
        }
        
        $and     =  ($this->isadmin!=1)?" and a1.ag_fid=".$this->myad_groupid:"";   //非超级管理员只能查看自己的子权限组
        
        $totalrecord = $this->db->getOne($this->db->Query("select count(*) as nums from " . get_table("admin_group") . ' as a1 
                    left join '.get_table("admin").' as a2  
                    on a1.ag_userid = a2.sysid where 1 '.$and));
        $sql = 'select a1.sysid,a1.ag_groupname,a1.ag_rightid,a1.ag_addtime,a1.ag_content,a2.a_name,a2.a_truename from '.get_table('admin_group').' as a1 
                    left join '.get_table("admin").' as a2  
                    on a1.ag_userid = a2.sysid where 1 '.$and;

        $rst = $this->db->Query($sql);
        $dataArr = $this->db->getAll($rst);
        $grouplist = array();
        $i = 0;
        if(empty($dataArr)){
            $val['ag_groupname'] = '你没有可操作（或分配）的权限组';
            $grouplist[] = $val;
        }else{
            foreach ($dataArr as $val) {//格式化时间，并将权限分组
                $val['ag_addtime'] = date("Y-m-d <br> H:m:s",$val['ag_addtime']);
                $i++;
                $val['serial'] = $i;
                $sql2 = 'select ar_rightname from '.get_table("admin_right").' where ar_rightid in('.addquote(',', $val[ag_rightid],'"').')';
                $rst2 = $this->db->Query($sql2);
                $dataArr2 = $this->db->getAll($rst2);
                $val['ag_rightid'] = '';//array_rebuild($dataArr2,','); //将多维数组转成字符串
                $i=1;
                foreach($dataArr2 as $value){
                    $val['ag_rightid'] .= "<li style='float: left; width: 165px; height: 20px;list-style:none;'>".$i.".".$value['ar_rightname']."</li>";
                    $i++;
                }
                $val['editbutton'] = "<a href='index.php?module=permigroup&method=edit_group&groupid=".$val['sysid']."&act=edit'>[修改]</a>&nbsp;&nbsp;&nbsp;<a href='index.php?module=permigroup&method=delete_group&name=".$val['a_name']."&groupid=".$val['sysid']."' onClick='return deleteGroup();'>[删除]</a>";
                $val['ag_groupname'] = $val['ag_groupname']."[".$val['sysid']."]"."[看权限]";
                $right_arr[]=array(
                    $val['ag_groupname'],
                    $val['ag_addtime'],
                    $val['a_name'],
                    $val['a_truename'],
                    $val['ag_content'],
                    $val['editbutton'],
                );
                $right_arrs[$val['sysid']] = $val['ag_rightid'];
            }
        }
        $this->assign("type","grouplist");
        $this->assign("right_arr",get_json_encode($right_arrs));
        $this->assign('grouplist',get_json_encode($right_arr));
        $this->display('permisson.html');
    }

    //编辑权限组
    public function edit_group()
    {
        if($this->isadmin!=1 && !$this->checkright("edit_group")){
            showinfo("仅超级管理员才能够执行该操作。","",2);
        }
        
        $act   = get_param("act");
		if($act == "update")
		{
			$rights    = get_array("rightid");
			$gsysid    = get_param("gsysid","int");
			$groupname = get_param("groupname");
			if(empty($groupname))
			{
                showinfo("权限组不能为空","",3);
			}
			if(empty($rights))
			{
                showinfo("权限不能为空","",3);
			}
            //判断是否非法增加其他权限
            $diff = @array_diff(get_array("rightid",',',true),$this->myrights);
            if(empty($diff)||$this->isadmin==1){
                $data['ag_rightid']   = get_array("rightid",',',true);//转换成字符串，并且消除重复
                $data['ag_groupname'] = get_param("groupname");
                $data['ag_jmenu']     = get_json_encode(get_tree($this->db,'',false,false,$rights));
                $data['ag_uptime']    = THIS_DATETIME;
                $data['ag_upid']      = $this->my_admin_id;
                $data['ag_content']   = get_param("groupcont");
                $sql                  = "select sysid from ".get_table("admin_group")." where ag_groupname='".$data['ag_groupname']."' and sysid!=".$gsysid;
                $query                = $this->db->query($sql);
                $isexits              = $this->db->getOne($query);
                if($isexits){
                    $this->admin_log("修改新权限组".$data['ag_groupname']."失败，原因：权限组已存在");
                    showinfo("该权限组已存在!","index.php?module=permigroup&method=group_list",4);
                }
                //权限组当前菜单
                $rightid_group = get_info($this->db, "admin_group", array('ag_rightid'), " and sysid =".$gsysid);
                $rightid_group = explode(',', $rightid_group['ag_rightid']);
                $rightid_group_new = explode(',', $data['ag_rightid']);
                //获取当前选为不显示的权限菜单
                $sql = "select sysid,a_rightid from ".get_table('admin')." where a_groupid = $gsysid";
                $user_q = $this->db->Query($sql);
                while ($row = $this->db->FetchArray($user_q)) {
                    if(empty($row['a_rightid'])){//所有权限未选中，则跳过
                        continue;
                    }
                    $rightid_user = explode(',', $row['a_rightid']);
                    $rightid_diff = array_diff($rightid_group,$rightid_user);
                    if(empty($rightid_diff)){//所有权限已选中，则直接同步新的权限
                        $user_data = array(
                            'a_rightid' => implode(',', $rightid_group_new),
                            'a_jmenu' => get_json_encode(get_tree($this->db,'',false,false,$rightid_group_new))
                        );
                    }else{//部分权限未选中，则只同步新增加的权限
                        $rightid_user_new = array_diff($rightid_group_new,$rightid_diff);
                        $user_data = array(
                            'a_rightid' => implode(',', $rightid_user_new),
                            'a_jmenu' => get_json_encode(get_tree($this->db,'',false,false,$rightid_user_new))
                        );
                    }
                    
                    $res  =  update_record($this->db, 'admin',$user_data,'',' and sysid = '.$row['sysid']);
                }
                
                $where['sysid'] =  $gsysid;
                $res  =  update_record($this->db, 'admin_group',$data,$where,'');
                if ($res){
                                $message = '成功修改权限组:'.$data['ag_groupname'];
                                $this->admin_log($message);
                                $str = "修改权限组成功。";
                }else{
                                $message = '修改权限组,失败:数据更新错误';
                                $this->admin_log($message);
                                $str = "修改权限组失败。";
                }
            }else{
                $message = '修改权限组,失败:非法传递参数';
                $this->admin_log($message);
                $str = "修改权限组失败，请按规定操作。";
            }

            showinfo($str,"index.php?module=permigroup&method=group_list",4);		
		}
        $gsysid = get_param('groupid','int');

        $groupsql = 'select sysid,ag_fid,ag_groupname,ag_rightid,ag_addtime,ag_userid,ag_content from '.get_table('admin_group').' where sysid = '.$gsysid;
		$rst = $this->db->Query($groupsql);
		$group = $this->db->getOne($rst);
		$rights = array();
		$rights = explode(',', $group['ag_rightid']); //分割权限id
        
        //非管理员只能操作用户组的子权限组
        if($group['ag_fid']!=$this->myad_groupid && $this->isadmin!=1){
            showinfo("请按规定操作。","",2);
        }
        
        $and     =  ($this->isadmin!=1)?" and ar_rightid in(".addquote(',',$this->myrights).")":"";   //非超级管理员只显示当前组权限
        $sql   =   "select sysid as menuid,ar_rightid,ar_rightname as menuname,ar_parentid as pid,ar_url as url,ar_ismenu
				 from ".get_table("admin_right")." where  ar_islock=2 $and order by sysid";
		$query = $this->db->query($sql);
		$result = $this->db->getAll($query);
        $menu  = getTrees($result);
        foreach ($menu['menus'] as $gpk => $gpv) {
            if (isset($gpv['menus']) && count($gpv['menus']) > 0) {
                foreach ($gpv['menus'] as $pk => $pv) {
                    $pcheckd = (in_array($pv['ar_rightid'], $rights)) ? 'checked' : '';

                    $list[$pv['pid']]['p'][$pv['menuid']] = "<label><input type='checkbox' " . $pcheckd . " onclick='gets(".$pv['menuid'].");getp(".$gpv['menuid'].");' name='rightid[]' value='" . $pv['ar_rightid'] . "' class='" . $gpv['menuid'] . "oo box " . $gpv['menuid'] . "c " . $gpv['menuid'] . "_" . $pv['menuid'] . "pp' id='" . $pv['menuid'] . "c' alt='1'><span class='text' for='" . $pv['ar_rightid'] . "c'>" . $pv['menuname'] . "</span></label>";

                    if (isset($pv['child']) && count($pv['child']) > 0) {
                        $list[$pv['pid']]['p'][$pv['menuid']] .= "<label class='box'>─▶</label>";
                        foreach ($pv['child'] as $ck => $cv) {
                            $ccheckd = (in_array($cv['ar_rightid'], $rights)) ? 'checked' : '';
                            if($cv['ar_ismenu']==2){
                               $color = "style='color:red'";
                            }else{
                               $color = "";
                            }
                            $list[$pv['pid']]['p'][$pv['menuid']] .= "<label><input type='checkbox' " . $ccheckd . " onclick='getpp(" . $gpv['menuid'] . "," . $pv['menuid'] . ")' name='rightid[]' value='" . $cv['ar_rightid'] . "' class='" . $pv['menuid'] . "o box " . $gpv['menuid'] . "c' id='" . $cv['ar_rightid'] . "c' alt='1'><span class='text' $color for='" . $cv['ar_rightid'] . "c'>" . $cv['menuname'] . "</span></label>";
                        }
                        $list[$pv['pid']]['p'][$pv['menuid']] .='<br/>';
                    }
                }

                $lsit[$pv['pid']]['p'][$pv['menuid']] ='<label><input type="checkbox" value="2" name="terrace[]"><span class="text">123</span></label>';
                $gcheckd = (in_array($gpv['ar_rightid'],$rights))? 'checked':'';
                $list[$gpv['menuid']]['gp'] = "<label><input type='checkbox' " . $gcheckd . " onclick='get(" . $gpv['menuid'] . ")' value='" . $gpv['ar_rightid'] . "'  id='" . $gpv['menuid'] . "p' class='p " . $gpv['menuid'] . "p " . $gpv['menuid'] . "pp' name='rightid[]' alt='1' /><span class='text'>".$gpv['menuname'].'</span></label>';
            }
        }
        $this->assign("groupname",$group['ag_groupname']);
        $this->assign("groupcontent",$group['ag_content']);
        $this->assign("gsysid",$gsysid);
        $this->assign("type","editgroup");
		$this->assign('list',$list);
        $this->display('permisson.html');
    }

    //删除权限组
    public function delete_group()
    {
        if($this->isadmin!=1){
            showinfo("仅超级管理员才能够执行该操作。","",2);
        }
        $where['sysid'] = get_param("groupid","int");
        $name           = get_param("name");
		if (!empty($where['sysid'])){
			$rst = delete_record($this->db, 'admin_group', $where);
		}
		if ($rst){
			$message = '成功删除权限组,(名称'.$name.',自动编号'.$where['sysid'].')';
			$this->admin_log($message);
			$str = "删除权限组成功。";
		}else{
			$message = '删除编号为权限组'.$name.',失败:更新数据出错';
			$this->admin_log($message);
			$str = "删除权限组失败。";
		}
                        showinfo($str,"index.php?module=permigroup&method=group_list",4);
    }

}
?>


