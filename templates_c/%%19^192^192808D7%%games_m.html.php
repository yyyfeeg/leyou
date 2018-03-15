<?php /* Smarty version 2.6.20, created on 2018-03-13 14:06:06
         compiled from ../m/games_m.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="wap-font-scale" content="no" />
    <!-- 禁止浏览器优化 -->
    <meta name="full-screen" content="yes">
    <!-- UC强制全屏 -->
    <meta name="x5-fullscreen" content="true">
    <!-- QQ强制全屏 -->
    <meta name="viewport" content="width=640, target-densitydpi=device-dpi, minimum-scale=0.5, maximum-scale=2, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>欢乐游戏 - 游戏中心</title>
    <meta name="Keywords" content="" />
    <meta name="Description" content="" />
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/css/common.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/css/index.css" rel="stylesheet" type="text/css">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/js/jquery.handle.js" type="text/javascript"></script>
    <script src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/js/jquery.slider.js" type="text/javascript"></script>
    <script src="<?php echo $this->_tpl_vars['web_url']; ?>
/templates/mobile/js/dropload.min.js" type="text/javascript"></script>
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
                <p>产品中心</p>
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
                <!--导航内容 end-->
            </div>
        </div>
        <!--头部 end-->
        <!--内容-->
        <div class="content">
            <!--轮播图-->
            <div class="banner banner_1">
                <div class="flexslider banner_content">
                    <ul class="slides">
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
                        <li><a href="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['jurl']; ?>
" title="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['title']; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['web_url']; ?>
<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['purl']; ?>
" width="600" height="252" alt="<?php echo $this->_tpl_vars['index_ppt'][$this->_sections['index']['index']]['title']; ?>
" /></a></li>
                        <?php endfor; endif; ?>
                    </ul>
                </div>
                <script type="text/javascript">
                $(function() {
                    $('.flexslider').flexslider({
                        animation: "slide",
                        directionNav: false,
                        slideshowSpeed: 3000,
                        start: function(slider) {}
                    });
                });
                clearInterval(timer);
                var timer = setInterval(function() {
                    $('.flexslider').flexslider('play');
                }, 3000);
                </script>
            </div>
            <!--轮播图 end-->
            <!--游戏-->
            <div class="three_package lanren" style="margin-bottom: 6rem;">
                <!--游戏内容-->
                <div class="package_content hidden">
                    <?php unset($this->_sections['index']);
$this->_sections['index']['name'] = 'index';
$this->_sections['index']['loop'] = is_array($_loop=$this->_tpl_vars['index_games']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <div class="package_1">
                        <span><a href="<?php echo $this->_tpl_vars['index_games'][$this->_sections['index']['index']]['gwurl']; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['index_games'][$this->_sections['index']['index']]['icon']; ?>
" width="177" /></a></span>
                        <ul>
                            <li class="big">
                                <a href="<?php echo $this->_tpl_vars['index_games'][$this->_sections['index']['index']]['gwurl']; ?>
" target="_blank">
                                    <?php echo $this->_tpl_vars['index_games'][$this->_sections['index']['index']]['gname']; ?>

                                </a>
                            </li>
                            <p>
                                <?php echo $this->_tpl_vars['index_games'][$this->_sections['index']['index']]['desc']; ?>

                            </p>
                        </ul>
                        <i><a href="<?php echo $this->_tpl_vars['index_games'][$this->_sections['index']['index']]['azurl']; ?>
">下载</a></i>
                    </div>
                    <?php endfor; endif; ?>
                    <!-- <div class="package_1">
                    <span><a href="#" target="_blank"><img src="../skin0/images/tusw.png" width="177" /></a></span>
                    <ul>
                        <li class="big"><a href="#" target="_blank">《神契 幻奇谭》</a></li>
                        <p>《神契》是漫画《神契 幻奇谭》改编动画作品,16年暑期各大网络平台播出。</p>
                    </ul>
                    <i><a href="#">下载</a></i>
                </div> -->
                </div>
                <ul class="list">数据加载中，请稍后...</ul>
                <div class="more" style="overflow: hidden;padding:10px;text-align: center;"><a style="display: block;width: 80px;padding:8px 0;color:#fff;margin:0 auto;background:#333;text-align:center;border-radius:3px;" href="javascript:;" onClick="lanren.loadMore();">加载更多</a></div>
                <!--游戏内容-->
            </div>
            <script>
            var _content = []; //临时存储li循环内容
            var lanren = {
                _default: 4, //默认显示图片个数
                _loading: 2, //每次点击按钮后加载的个数
                init: function() {
                    var lis = $(".lanren .hidden div");
                    $(".lanren ul.list").html("");
                    for (var n = 0; n < lanren._default; n++) {
                        lis.eq(n).appendTo(".lanren ul.list");
                    }
                    $(".lanren ul.list img").each(function() {
                        $(this).attr('src', $(this).attr('realSrc'));
                    })
                    for (var i = lanren._default; i < lis.length; i++) {
                        _content.push(lis.eq(i));
                    }
                    $(".lanren .hidden").html("");
                },
                loadMore: function() {
                    var mLis = $(".lanren ul.list div").length;
                    for (var i = 0; i < lanren._loading; i++) {
                        var target = _content.shift();
                        if (!target) {
                            $('.lanren .more').html("<p>全部加载完毕...</p>");
                            break;
                        }
                        $(".lanren ul.list").append(target);
                        $(".lanren ul.list img").eq(mLis + i).each(function() {
                            $(this).attr('src', $(this).attr('realSrc'));
                        });
                    }
                }
            }
            lanren.init();
            </script>
            <!--游戏 end-->
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
        <!--返回顶部-->
        <div class="back_to_top" style="display:none">
            <a id="gotop" href="javascript:;" title="返回顶部"></a>
        </div>
        <!--返回顶部 end-->
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
        <!-- 弹出下载提示 end -->
        <!-- 底部导航 end-->
    </div>
    <!--DOM在CSS后避免reflow-->
    <div id="orientLayer" class="mod-orient-layer">
        <div class="mod-orient-layer__content">
            <i class="icon mod-orient-layer__icon-orient"></i>
            <div class="mod-orient-layer__desc">为了更好的体验，请使用竖屏浏览</div>
        </div>
    </div>
    <div class="video-box" id="mod_player"></div>
    <script>
    //暂时屏蔽外连接
    function show() {
        alert('敬请期待！');
    }
    </script>
</body>

</html>