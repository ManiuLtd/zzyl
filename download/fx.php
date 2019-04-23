<?php

use model\ShareModel;

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//控制浏览器缓存
header("Cache-Control: no-cache, must-revalidate");
header('Content-Type: text/html; charset=utf-8');
header("Pragma: no-cache");

//设置默认时区
date_default_timezone_set('Asia/Shanghai');

//自动加载帮助类
require_once dirname(__DIR__) . '/hm_ucenter/helper/LoadHelper.php';

$gameConfig = ShareModel::getInstance()->getGameConfig();
$getHomeConfig = ShareModel::getInstance()->getHomeConfig();
define('zzyl', 1);
include 'download.php';
exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"
          name="viewport" id="viewport"/>
    <title><?php echo $getHomeConfig['title']?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css?v=1.0.0"/>
    <link rel="stylesheet" type="text/css" href="Font/css/font-awesome.css"/>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript">
        //定时闪图
        function shan() {
            if ($('.text2').css('display') == 'block') {
                $('.text2').css('display', 'none');
                // alert($('.text2').css('display'));
            } else {
                $('.text2').css('display', 'block');
            }
        }
        setInterval("shan()", 500);
        $(document).ready(
            function () {
                var nowpage = 0;
                var max_len = 2;
                max_len -= 1;
                //给最大的盒子增加事件监听
                $(".container").swipe(
                    {
                        swipe: function (event, direction, distance, duration, fingerCount) {
                            if (direction == "up") {
                                nowpage = nowpage + 1;
                            } else if (direction == "down") {
                                nowpage = nowpage - 1;
                            }
                            if (nowpage > max_len) {
                                nowpage = max_len;
                            }
                            if (nowpage < 0) {
                                nowpage = 0;
                            }
                            $(".container").animate({"top": nowpage * -100 + "%"}, 400);
                            $(".page").eq(nowpage).addClass("cur").siblings().removeClass("cur");
                        }
                    }
                );
            }
        );

    </script>
</head>
<body onmousewheel="return false;" id="body">
<audio autoplay="autoplay" height="100" width="100" id="audio" loop="-1">
    <source src="./music.mp3" type="audio/mp3"/>
</audio>
<!--<h1 class="keTitle">至尊娱乐棋牌</h1>-->
<div class="weixin-tip"></div>
<!--效果html开始-->
<div class="container">
    <div style="display:none;" id="weixintishi">
        <img src="./images/weixintishi.png">
    </div>
    <div class="page page0 cur">
        <div class="button2">
            <div class="text2"><img src="./images/tishi.png"></i></div>
            <img style="margin-left: 22%;" height="70" width="151"
                 src="http://47.107.147.29/download/btn_download.png" onclick="download();">
        </div>
    </div>
    <div class="page page1">
        <div class="button2">
            <div class="text2"><img src="./images/tishi.png"></i></div>
            <img style="margin-left: 22%;" height="70" width="151"
                 src="http://47.107.147.29/download/btn_download.png" onclick="download();"></div>
    </div>
    <div class="page page2" style="width: 100%">
        <div class="button2">
            <div class="text2">
                <img src="./images/tishi.png"></i></div>
            <img style="margin-left: 22%;" height="70" width="151"
                 src="http://47.107.147.29/download/btn_download.png" onclick="download();">
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    Play = document.getElementById("audio");
    Play.play()
    var ua = window.navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        setInterval(function () {
            document.addEventListener("visibilitychange", function () {
                if (document.visibilityState != 'visible') {
                    Play.pause();
                } else {
                    Play.play()
                }
            });
        }, 1000)
    }
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) { //页面不可见状态
            // document.title = '你离开了';
            Play.pause();
        } else { //页面可见状态
            // document.title = '你回来了';
            Play.play();
        }
    });


    function download() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
//alert('请点击右上角选择在浏览器中打开');
            $('.weixin-tip').css('display', 'block');
        } else {
            var u = navigator.userAgent;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1 || u.indexOf('android') > -1; //android终端
            if (isAndroid) {
                location.href = "<?php echo $gameConfig['android_packet_address'] ? $gameConfig['android_packet_address'] : 'anzhuo.apk';?>"
            } else {
                var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if (isiOS) {
                    location.href = "<?php echo $gameConfig['apple_packet_address'];?>";
                } else {
                    location.href = "<?php echo $gameConfig['android_packet_address'] ? $gameConfig['android_packet_address'] : 'anzhuo.apk';?>"
                }
            }

        }
    }
    function ios_download() {
        //var ua = navigator.userAgent.toLowerCase();
        //if(ua.match(/MicroMessenger/i)=="micromessenger") {
//alert('请点击右上角选择在浏览器中打开');
        //$('.weixin-tip').css('display','block');
        //console.log('zz');
//        $('#weixintishi').css('display','block');
        //} else {
        // location.href="https://itunes.apple.com/us/app/至尊娱乐棋牌/id1274971104?mt=8"
        var u = navigator.userAgent;
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if (isiOS) {
            location.href = "<?php echo $gameConfig['apple_packet_address'];?>";
        } else {
            alert("请使用苹果手机下载该游戏")
        }

        //}
    }
    //    $('#body').on('click',function(){
    //    if($('#weixintishi').css('display') == 'block'){
    //   $('#weixintishi').css('display','none');
    //  }      
    //  });


</script>
</html>
