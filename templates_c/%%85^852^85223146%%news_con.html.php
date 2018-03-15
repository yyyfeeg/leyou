<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:06
         compiled from ../m/news_con.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="wap-font-scale"  content="no"/><!-- 禁止浏览器优化 -->
    <meta name="full-screen" content="yes"><!-- UC强制全屏 -->
    <meta name="x5-fullscreen" content="true"><!-- QQ强制全屏 -->
    <meta name="viewport" content="width=640, target-densitydpi=device-dpi, minimum-scale=0.5, maximum-scale=2, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>欢乐游戏 - 新闻资讯</title>
    <meta name="Keywords" content=""/>
    <meta name="Description" content=""/>
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/css/common.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/css/index.css" rel="stylesheet" type="text/css">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/js/jquery.handle.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['web_url']; ?>
/m/css/weui.css">
</head>
<body>
<!-- 移动端 -->
<div class="wap">
	<!--头部-->
    <div class="head">
	    <!--返回-->
	    <div class="index">
	        <a href="<?php echo $this->_tpl_vars['callback_url']; ?>
" title="返回"><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/back.png" /></a>
	    </div>
	    <!--返回 end-->
	    <!--新闻资讯-->
	    <div class="headline">
	        <p>新闻资讯</p>
	    </div>
	    <!--新闻资讯 end-->
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
                    <li class="nav_1"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/index.html" title="首页"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/首页.png" style="margin-top: 3px;" /></span><p>首页</p></a></li>
                    <li class="nav_2"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/news/index.html" title="新闻"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/新闻.png" style="margin-top: 3px;" /></span><p>新闻</p></a></li>
                    <li class="nav_3 "><a href="javascript:void(0)" onclick="show();" title="礼包"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/礼包.png" style="margin-top: 3px;" /></span><p>礼包</p></a></li>
                    <li class="nav_4 on"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/games/index.html" title="游戏"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/游戏.png" style="margin-top: 3px;" /></span><p>游戏</p></a></li>
                    <li class="nav_5 "><a href="javascript:void(0)" onclick="show();" title="用户"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/用户.png" style="margin-top: 3px;" /></span><p>用户</p></a></li>
                    <li class="nav_6 "><a href="javascript:void(0)" onclick="show();" title="客服"><span><img src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/客服.png" style="margin-top: 3px;" /></span><p>客服</p></a></li>
                </ul>
	        </div>
	        <!-- 导航内容 end-->
	    </div>
    </div>   
    <!--头部 end-->

    <!--内容-->
    <div class="content">
    	<!--新闻列表-->
    	<div class="title"><h3 style="line-height: 6rem;text-align: center;font-size: 1.9rem;"><?php echo $this->_tpl_vars['content_arr']['title']; ?>
</h3></div>
    	<div class="write_time"><p style="font-size: 1.5rem;line-height: 3rem;text-align: center;margin-top: -1rem;"><?php echo $this->_tpl_vars['content_arr']['printtime']; ?>
</p></div>
    	<div class="article_con" style="font-size: 1.4rem;margin-bottom: 8rem;">
    	<?php echo $this->_tpl_vars['content_arr']['contents']; ?>

        <!--判断是否有上一篇文章-->
        <?php if ($this->_tpl_vars['content_arr']['uppen'] == 'NO'): ?>
        <p style="margin-top: 1rem;">上一篇：<span style="color: #139BD8;display: inline;text-decoration: none;">没有了</span></p></br>
        <?php else: ?>
        <p style="margin-top: 1rem;">上一篇：<a href="<?php echo $this->_tpl_vars['content_arr']['uppen']['url']; ?>
" style="color: #139BD8;display: inline;text-decoration: none;"><?php echo $this->_tpl_vars['content_arr']['uppen']['title']; ?>
</a></br>
        <?php endif; ?>
        <!--判断是否有下一篇文章-->
        <?php if ($this->_tpl_vars['content_arr']['nextpen'] == 'NO'): ?>
    	<p style="margin-top: 1rem;">下一篇：<span style="color: #139BD8;display: inline;text-decoration: none;">没有了</span></p>
        <?php else: ?>
        <p style="margin-top: 1rem;">下一篇：<a href="<?php echo $this->_tpl_vars['content_arr']['nextpen']['url']; ?>
" style="color: #139BD8;display: inline;text-decoration: none;"><?php echo $this->_tpl_vars['content_arr']['nextpen']['title']; ?>
</a></p>
        <?php endif; ?>
    	</div>
		<!--新闻列表 end-->
    </div>
    <!--内容 end-->

    <!-- 底部导航 -->
    <!--底部-->
	<div class="foot">
	<ul>
        <li class="home"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/index.html" title="首页"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/首页.png" /></span><p>首页</p></a></li>
        <li class="news"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/news/index.html" title="新闻"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/新闻.png" /></span><p>新闻</p></a></li>
        <li class="game on"><a href="<?php echo $this->_tpl_vars['web_url']; ?>
/html/mobile/games/index.html" title="游戏"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/游戏.png" /></span><p>游戏</p></a></li>
        <li class="user"><a href="javascript:void(0)" onclick="show();" title="用户"><span><img style="margin-top: 6px;" src="<?php echo $this->_tpl_vars['web_url']; ?>
/skin0/images/用户.png" /></span><p>用户</p></a></li>
    </ul>
	</div>
	<!--底部 end-->
	<script>
    //暂时屏蔽外连接
    function show() {
        alert('敬请期待！');
    }
    </script>
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
	        <div class="mod-orient-layer__desc">为了更好的体验，请使用竖屏浏览</div>
	    </div>
	</div>
	<div class="video-box" id="mod_player"></div>
</body>
</html>