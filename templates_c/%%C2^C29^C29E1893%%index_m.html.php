<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:05
         compiled from ../m/index_m.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="wap-font-scale"  content="no"/><!-- 禁止浏览器优化 -->
    <meta name="full-screen" content="yes"><!-- UC强制全屏 -->
    <meta name="x5-fullscreen" content="true"><!-- QQ强制全屏 -->
    <meta name="viewport" content="width=640, target-densitydpi=device-dpi, minimum-scale=0.5, maximum-scale=2, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>欢乐游戏 - 首页</title>
    <meta name="Keywords" content=""/> 
    <meta name="Description" content=""/>
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/css/common.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/css/index.css" rel="stylesheet" type="text/css">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/js/jquery.handle.js" type="text/javascript"></script>
    <!-- <script>$(function(){get_hot_gifts();});</script> -->
</head>
<body>
<!-- 移动端 -->
<div class="wap">
    <!--头部-->
    <div class="head">
        <!--logo-->
        <div class="logo">
            <a href="<?php echo $this->_tpl_vars['web_url']; ?>
" title="欢乐游戏"><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/images/leyou_logo.png" width="100%" height="100%" alt=""></a>
        </div>
        <!--logo end-->
        <!--导航-->
        <div class="nav">
            <!--导航按钮-->
            <div class="nav_btn nav" style="height: 100px;">
                <button id="top_menu" class="btn-nav">
                    <span class="icon-bar top"></span>
                    <span class="icon-bar middle"></span>
                    <span class="icon-bar bottom"></span>
                </button>
            </div>
            <!--导航按钮 end-->
        </div>
        <!--导航 end-->
        <div class="nav-content hideNav hidden">
            <!--导航内容-->
            <div class="nav_content effect_1 ggg" id="lxt">
                <ul>
                    <li class="nav_1 on"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/index.html" title="首页"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/首页.png" style="margin-top: 3px;" /></span><p>首页</p></a></li>
                    <li class="nav_2 "><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/news/index.html" title="新闻"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/新闻.png" style="margin-top: 3px;" /></span><p>新闻</p></a></li>
                    <li class="nav_3 "><a href="javascript:void(0)" onclick="show();" title="礼包"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/礼包.png" style="margin-top: 3px;" /></span><p>礼包</p></a></li>
                    <li class="nav_4 "><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/games/index.html" title="游戏"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/游戏.png" style="margin-top: 3px;" /></span><p>游戏</p></a></li>
                    <li class="nav_5 "><a href="javascript:void(0)" onclick="show();" title="用户"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/用户.png" style="margin-top: 3px;" /></span><p>用户</p></a></li>
                    <li class="nav_6 "><a href="javascript:void(0)" onclick="show();" title="客服"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/客服.png" style="margin-top: 3px;" /></span><p>客服</p></a></li>
                </ul>
            </div>
            <!--导航内容 end-->
        </div>
    </div>    
    <!--头部 end-->

    <!--内容-->
    <div class="content">
        <!--用户信息-->
        
        <!--用户信息 end-->
        
        <!--轮播图-->
        <div class="slider-banner" id='sliderA'>
            <div class="swipe">
                <div class="swipe-wrap">
                <?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['index_ppt']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['index']['show'] = true;
$this->_sections['index']['max'] = $this->_sections['index']['loop'];
$this->_sections['index']['step'] = 1;
$this->_sections['index']['start'] = $this->_sections['index']['step'] > 0 ? 0 : $this->_sections['index']['loop']-1;
if ($this->_sections['index']['show']) {
    $this->_sections['index']['total'] = $this->_sections['index']['loop'];
    if ($this->_sections['index']['total'] == 0)
        $this->_sections['index']['show'] = false;
} else
    $this->_sections['index']['total'] = 0;
if ($this->_sections['index']['show']):

            for ($this->_sections['index']['index'] = $this->_sections['index']['start'], $this->_sections['index']['iteration'] = 1;
                 $this->_sections['index']['iteration'] <= $this->_sections['index']['total'];
                 $this->_sections['index']['index'] += $this->_sections['index']['step'], $this->_sections['index']['iteration']++):
$this->_sections['index']['rownum'] = $this->_sections['index']['iteration'];
$this->_sections['index']['index_prev'] = $this->_sections['index']['index'] - $this->_sections['index']['step'];
$this->_sections['index']['index_next'] = $this->_sections['index']['index'] + $this->_sections['index']['step'];
$this->_sections['index']['first']      = ($this->_sections['index']['iteration'] == 1);
$this->_sections['index']['last']       = ($this->_sections['index']['iteration'] == $this->_sections['index']['total']);
?>
                    <div class="swipe-con">
                        <a href="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['jurl']; ?>
" title="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['title']; ?>
" target="_blank" >
                            <img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['purl']; ?>
" width="100%" height="387" alt="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['title']; ?>
">
                        </a>
                    </div>
                <?php endfor; endif; ?>
                </div>
            </div>
            <div class="swipe_nav"></div>
        </div>
        <!--轮播图 end-->
        
        <!--游戏推荐-->
        <div class="recommended_games">
            <div class="game_top">
                <div class="games_title recommended">
                    <p style="font-weight: bold;"><b style="color: rgb(15,150,213);">▍</b>热门游戏</p>
                </div>
                <span><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/games/index.html" title="更多">+更多</a></span>
            </div>
            <div class="recommended_content">
                <?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['index_game']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['index']['show'] = true;
$this->_sections['index']['max'] = $this->_sections['index']['loop'];
$this->_sections['index']['step'] = 1;
$this->_sections['index']['start'] = $this->_sections['index']['step'] > 0 ? 0 : $this->_sections['index']['loop']-1;
if ($this->_sections['index']['show']) {
    $this->_sections['index']['total'] = $this->_sections['index']['loop'];
    if ($this->_sections['index']['total'] == 0)
        $this->_sections['index']['show'] = false;
} else
    $this->_sections['index']['total'] = 0;
if ($this->_sections['index']['show']):

            for ($this->_sections['index']['index'] = $this->_sections['index']['start'], $this->_sections['index']['iteration'] = 1;
                 $this->_sections['index']['iteration'] <= $this->_sections['index']['total'];
                 $this->_sections['index']['index'] += $this->_sections['index']['step'], $this->_sections['index']['iteration']++):
$this->_sections['index']['rownum'] = $this->_sections['index']['iteration'];
$this->_sections['index']['index_prev'] = $this->_sections['index']['index'] - $this->_sections['index']['step'];
$this->_sections['index']['index_next'] = $this->_sections['index']['index'] + $this->_sections['index']['step'];
$this->_sections['index']['first']      = ($this->_sections['index']['iteration'] == 1);
$this->_sections['index']['last']       = ($this->_sections['index']['iteration'] == $this->_sections['index']['total']);
?>
                <div class="recommended_1">
                    <span>
                        <a href="javascript:;" title="">
                        <img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['index_game'][$this->_sections['index']['index']]['icon']; ?>
" width="100%" alt="<?php echo $this->_tpl_vars['index_game'][$this->_sections['index']['index']]['gname']; ?>
" />
                        </a>
                    </span>
                    <div class="hot_font">
                        <p class="hotgames_title"><?php echo $this->_tpl_vars['index_game'][$this->_sections['index']['index']]['gname']; ?>
</p>
                        <p class="hotgames_txt"><?php echo $this->_tpl_vars['index_game'][$this->_sections['index']['index']]['desc']; ?>
</p>
                        <div class="hot_bor">
                            <div style="float: right;"><a href="<?php echo $this->_tpl_vars['index_game'][$this->_sections['index']['index']]['gwurl']; ?>
" class="hot_logoin" target="_blank">进入官网</a></div>
                        </div>
                    </div>
                </div>
                <?php endfor; endif; ?>
            </div>
        </div>
        <!--游戏推荐 end-->
        
        <!--游戏礼包-->
        <div class="popular_gifts">
            <div class="game_top" style="display: none;">
                <div class="games_title recommended">
                    <p style="font-weight: bold;"><b style="color: rgb(15,150,213);">▍</b>游戏礼包</p>
                </div>
                <span><a href="" title="更多">+更多</a></span>
            </div>
            <div class="popular_content">
                <div class="popular popular_1" style="display: none;">
                    <span>
                        <a href="#"><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/tusw.png" width="100%" /></a>
                    </span>
                    <div class="popular_right">
                        <p><a href="#">新手大礼包</a></p>
                        <i><a href="#">烈火荣耀</a></i>
                        <em><a href="javascript:;" onclick="">领取</a></em>
                    </div>
                </div>
                <div class="popular_more" style="display: none;"><a href="#">+更多礼包</a></div>
            </div>
        </div>
        <!--热门礼包 end-->
        
        <!--底部-->
        <div class="footer_link">
        	<div style="padding: 4px;font-size: 20px;">
			<span style="font-weight: bolder;">友情链接：</span>
			<a style="display: inline;" target="_blank" href="http://www.app178.com/">APP178</a>&nbsp;&nbsp;&nbsp;
			<a style="display: inline;" target="_blank" href="http://www.gk99.com/">游戏港口</a>&nbsp;&nbsp;&nbsp;
			<a style="display: inline;" target="_blank" href="http://www.kaifu.com/">开服网</a>
            </div>
        </div>
        <div class="footerr">
            <p style="padding-top: 20px;">Copyright 2013 ©广州乐游.All Rights Reserved</p>
            <p>icp备案号：粤ICP备17102407号</p>
            <p>地址：广州市天河区中山大道建工路13天信楼4楼406房</p>
            <p>电话：86-021-60331122.</p>
        </div>
        <!--热门礼包 end-->
        
        
        <!--用户退出-->
        <div class="user_exits" style="display: none">
        </div>
        <!--用户退出 end-->
    </div>
    <!--内容 end-->
    
    <!-- 底部导航 -->
    <!--底部-->
    <div class="foot">
        <ul>
            <li class="home on"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/index.html" title="首页"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/首页.png" /></span><p>首页</p></a></li>
            <li class="news"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/news/index.html" title="新闻"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/新闻.png" /></span><p>新闻</p></a></li>
            <li class="game"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/games/index.html" title="游戏"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/游戏.png" /></span><p>游戏</p></a></li>
            <li class="user"><a href="javascript:void(0)" onclick="show();" title="用户"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/用户.png" /></span><p>用户</p></a></li>
        </ul>
    </div>
    <!--底部 end-->
    <!--弹出框-->
    <div class="pop_up" style="display:none;">
        <!--信息弹出窗-->
        <div id="all_tips" class="pop_up_content" style="display:none;">
            <div class="pop_up_title">
                <p>提 示</p>
                <span><a href="javascript:;" title="关闭" onclick="$('#all_tips').hide(300);$('.pop_up').hide();"></a></span>
            </div>
            <div class="pop_up_writing">
                <p></p>
            </div>
            <div class="pop_up_confirm">
                <p><a href="javascript:;" title="确认" onclick="$('#all_tips').hide(300);$('.pop_up').hide();">确认</a></p>
            </div>
        </div>
        <!--信息弹出窗 end-->
        
        <!--cdkey弹出窗-->
        <div id="cdkey_tips" class="pop_up_content" style="display:none;">
            <div class="pop_up_writing pop_up_writing_1">
                <p>您的礼包号是：</p>
                <span><a></a></span>
                <i>请长按序列号进行复制</i>
                <i>然后在游戏中输入兑换礼包</i>
            </div>
            <div class="pop_up_confirm pop_up_confirm1">
                <p><a href="javascript:;" title="关闭" onclick="$('#cdkey_tips').hide(300);$('.pop_up').hide();">关闭</a></p>
            </div>
        </div>
        <!--cdkey弹出窗 end-->
    </div>
    <!--弹出框-->
    <!-- 弹出下载提示 -->
    <div class="popupbox_download" style="display:none">        
    </div>
    <!-- 弹出下载提示 end -->    <!-- 底部导航 end-->
    </div>
    <!--DOM在CSS后避免reflow-->
    <div id="orientLayer" class="mod-orient-layer">
        <div class="mod-orient-layer__content">
            <i class="icon mod-orient-layer__icon-orient"></i>
            <div class="mod-orient-layer__desc">为了更好的体验，请使用竖屏浏览1</div>
        </div>
    </div>
    <div class="video-box" id="mod_player"></div>
    <script src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/js/jquery.swip.js" type="text/javascript"></script>
    <script>Index.init('#sliderA');</script>
    <script>
    //暂时屏蔽外连接
    function show() {
        alert('敬请期待！');
    }
    </script>
</body>
</html>