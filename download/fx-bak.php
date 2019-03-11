<?php 
include '../common/config.php';
include '../common/db.php';
$db = new db();
$sql = "select * from web_gameconfig";
$arr = $db -> getAll($sql);
$config = [];
foreach($arr as $k => $v){
  $config[$arr[$k]['key']] = $arr[$k]['value'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport" />
<title>至尊娱乐棋牌</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="Font/css/font-awesome.css" />
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
<script type="text/javascript">
        //定时闪图
        function shan(){
          if($('.text2').css('display') == 'block'){
            $('.text2').css('display','none');
            // alert($('.text2').css('display'));
          }else{
             $('.text2').css('display','block');
          }
        }   
        setInterval("shan()",500);
    $(document).ready(

        function() {
            var nowpage = 0;
            //给最大的盒子增加事件监听
            $(".container").swipe(
                {
                    swipe:function(event, direction, distance, duration, fingerCount) {
                         if(direction == "up"){
                            nowpage = nowpage + 1;
                         }else if(direction == "down"){
                            nowpage = nowpage - 1;
                         }
                         if(nowpage > 2){
                            nowpage = 2;
                         }
                         if(nowpage < 0){
                            nowpage = 0;
                         }
                        $(".container").animate({"top":nowpage * -100 + "%"},400);
                        $(".page").eq(nowpage).addClass("cur").siblings().removeClass("cur");
                    }
                }
            );
        }
    );

</script>
</head>
<body onmousewheel="return false;" id="body">
<audio autoplay="autoplay" height="100" width="100"  loop="-1">
  <source src="./music.mp3" type="audio/mp3" />
  </audio>
<!--<h1 class="keTitle">至尊娱乐棋牌</h1>-->
<div class="weixin-tip"></div>
<!--效果html开始-->
<div class="container">
    <div style="display:none;" id="weixintishi">
      <img src="./images/weixintishi.png">
    </div>
    <div class="page page0 cur">
        <div class="button2"> <div class="text2" ><img src="./images/tishi.png"></div><img width="151px"  src="./images/anzhuo.png" onclick="download();">&emsp;&emsp;<img width="151px"  src="./images/apple.png" onclick="ios_download();">
        </div>
    </div>
    <div class="page page1">
    <div class="button2"> <div class="text2" ><img src="./images/tishi.png"></div><img width="151px" src="./images/anzhuo.png" onclick="download();">&emsp;&emsp;<img width="151" src="./images/apple.png" onclick="ios_download();"></div>
    </div>
    <div class="page page2">
    <div class="button2"> <div class="text2" ><img src="./images/tishi.png"></i></div><img width="151" src="./images/anzhuo.png"  onclick="download();">&emsp;&emsp;<img width="151"  src="./images/apple.png" onclick="ios_download();"></div>
    </div>
</div>
</body>
<script type="text/javascript">
  function download(){
      var ua = navigator.userAgent.toLowerCase();
      if(ua.match(/MicroMessenger/i)=="micromessenger") {
//alert('请点击右上角选择在浏览器中打开');
        $('.weixin-tip').css('display','block');
      } else {
        //location.href="http://haoyue.szhuomei.com/haotianqipai-106.apk"
     	 location.href="<?php echo $config['android_packet_address']?$config['android_packet_address']:'anzhuo.apk';?>"
	 }
    }
function ios_download(){
      //var ua = navigator.userAgent.toLowerCase();
      //if(ua.match(/MicroMessenger/i)=="micromessenger") {
//alert('请点击右上角选择在浏览器中打开');
	//$('.weixin-tip').css('display','block');
	//console.log('zz');
//        $('#weixintishi').css('display','block');
      //} else {
       // location.href="https://itunes.apple.com/us/app/至尊娱乐棋牌/id1274971104?mt=8"
	location.href="<?php echo $config['apple_packet_address'];?>";     
 //}
    }
//    $('#body').on('click',function(){
  //    if($('#weixintishi').css('display') == 'block'){
    //   $('#weixintishi').css('display','none');
    //  }      
  //  });
</script>
</html>
