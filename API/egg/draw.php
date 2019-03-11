<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1 maximum-scale=2, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Add to Home">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="x-rim-auto-match" content="none">
<title>砸金蛋</title>
<link href="./egg/css/publi.css" rel="stylesheet" type="text/css">
<link href="./egg/css/chuyouwuyi01.css" rel="stylesheet" type="text/css">
<link href="./egg/css/liMarquee.css" rel="stylesheet" type="text/css">
<script src="./egg/js/jquery1.8.3.min.js"></script>
<script src="./egg/js/jquery.liMarquee.js"></script>
<script type="text/javascript">
	var phoneWidth = parseInt(window.screen.width);
	var phoneScale = phoneWidth / 640;
	var ua = navigator.userAgent;
	if (/Android (\d+\.\d+)/.test(ua)) {
		var version = parseFloat(RegExp.$1);
		// andriod 2.3
		if (version > 2.3) {
			document.write('<meta name="viewport" content="width=640, minimum-scale = ' + phoneScale + ', maximum-scale = ' + phoneScale + ', target-densitydpi=device-dpi">');
			// andriod 2.3以上
		} else {
			document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
		}
		// 其他系统
	} else {
		document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
	}
</script>
<script type="text/javascript">
    $(function(){
        $('#dowebok').liMarquee();
    });
</script>
</head>
<body>
<section class="zadan">
	<div class="zd_middle">
		<div class="zd_zadan">
			<div class="zd_bg">
				<img src="./egg/img/160615zadan/zd_cion18.png"  class="zd_bg01">
				<img src="./egg/img/160615zadan/zd_cion18.png"  class="zd_bg02">
			</div>
			<div class="zd_jindan ">
				<img src="./egg/img/160615zadan/zd_cion16.png">
			</div>
			<ul class="zd_bigcaidai">
				<li class="cardai01 cardai">
					<img src="./egg/img/160615zadan/zd_cion15.png">
				</li>
				<li class="cardai02 cardai">
					<img src="./egg/img/160615zadan/zd_cion14.png">
				</li>
				<li class="cardai03 cardai">
					<img src="./egg/img/160615zadan/zd_cion13.png">
				</li>
				<li class="cardai04 cardai">
					<img src="./egg/img/160615zadan/zd_cion12.png">
				</li>
				<li class="cardai05 cardai">
					<img src="./egg/img/160615zadan/zd_cion11.png">
				</li>
				<li class="cardai06 cardai">
					<img src="./egg/img/160615zadan/zd_cion10.png">
				</li>
			</ul>
			<div class="zd_chuizi">
				<img src="./egg/img/160615zadan/zd_cion01.png">
			</div>
			<div class="zd_txt">
				<div style="font-size:30px;margin-top: -30px;color: white;margin-left:20px;margin-bottom:40px;"> 
					<p>userID:<?php echo $user['userID'];?></p>
					<p>金币数：<?php echo $user['money'];?></p>
					<p>今天还剩<?php echo $count;?>次机会</p>

				</div>
				<a href="javascript:;" id="submit" class="mom_btn tc">
					<img src="./egg/img/160615zadan/zd_cion07.png">
				</a>
			</div>
		</div>
		<div style="text-align:center;margin:20px 0; ">
			<center><font size="5px" color="yellow">获奖信息</font></center><br>
		   <marquee style="WIDTH: 420px; HEIGHT: 200px;vspace:30px;text-align: center;line-height:30px;" scrollamount="4" direction="up" scrolldelay="-100" >
			<p ><font color="white" size="4" >恭喜玩家小黄牛获得奖品<font color="yellow"><font color="yellow">小米移动电源</font ></p >
			<p ><font color="white" size="4" >恭喜玩家秋刀鱼获得奖品<font color="yellow">5万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家的滋味获得1奖品<font color="yellow">10张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家猫和你都想了解获得<font color="yellow">5张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家初恋的香味获得<font color="yellow">20万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家就这样被我们寻回获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家爱我别走获得<font color="yellow"><font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家如果你说获得<font color="yellow">10张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家你不爱我获得<font color="yellow">20张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家上车了获得<font color="yellow">20张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家收拾心情~获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家背上行李获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家我走了获得<font color="yellow">20万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家你算什么男人获得<font color="yellow">5张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家还爱着她获得<font color="yellow">20元手机充值卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家却不敢叫她再等获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家没差，你再继续认份获得<font color="yellow">雷蛇游戏鼠标</font ></p >
			<p ><font color="white" size="4" >恭喜玩家她会遇到更好的男人获得<font color="yellow">50元专属游戏礼包</font ></p >
			<p ><font color="white" size="4" >恭喜玩家才离开没多久就开始获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家担心你过得好不好获得<font color="yellow">小米移动电源</font ></p >
			<p ><font color="white" size="4" >恭喜玩家整个画面是你获得<font color="yellow">30张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家想你想的睡不着获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家就是开不了口让你知道获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家我一定会呵护着你也对你笑获得<font color="yellow">5张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家你已经远远离开获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家我也会慢慢走开获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家为什么还要我用获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家微笑来带过获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家怎么了获得<font color="yellow">1.3万金币安慰奖</font ></p >
			<p ><font color="white" size="4" >恭喜玩家你累了获得<font color="yellow">50元游戏专属大礼包</font ></p >
			<p ><font color="white" size="4" >恭喜玩家说好的获得<font color="yellow">10话费充值卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家幸福呢获得<font color="yellow">50万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家我累了获得<font color="yellow">华为P9</font ></p >
			<p ><font color="white" size="4" >恭喜玩家不说了获得<font color="yellow">10张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家爱淡了获得<font color="yellow">20万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家梦远了获得<font color="yellow">10万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家你说了所有的慌获得<font color="yellow">小米移动电源</font ></p >
			<p ><font color="white" size="4" >恭喜玩家我全都相信获得<font color="yellow">20万金币</font ></p >
			<p ><font color="white" size="4" >恭喜玩家简单的我爱你获得<font color="yellow">50张房卡</font ></p >
			<p ><font color="white" size="4" >恭喜玩家你却老不信获得<font color="yellow">雷蛇游戏鼠标</font ></p >
			<p ><font color="white" size="4" >恭喜玩家拜拜获得<font color="yellow">10万金币</font ></p >
			</p >
			</marquee >
		</div>

		<div style="margin:50px auto;width: 80%; ">
		<center><font size="5px" color="yellow">游戏规则</font></center><br>
		<p><font color="white" size="4">1.玩家金币数不得低于20万。</font></p><br>
		<p><font color="white" size="4">2.玩家每日抽奖机会有且只有5次。</font></p><br>
		<p><font color="white" size="4">3.玩家抽奖一次需耗费金币1.3万金币。</font></p><br>
		<p><font color="white" size="4">4.玩家中奖后游戏虚拟道具平台会下发至玩家游戏账户中，实物奖品需联系平台进行兑换。</font></p>
		</div>
		<div style="margin:30px auto;width:80%;">
			<center><font size="5px" color="yellow">奖品信息</font></center><br>
			<marquee style="WIDTH: 100%; HEIGHT: 200px;vspace:30px;text-align: center;line-height:30px;" scrollamount="10" direction="left" scrolldelay="-200" Behaviour="Scroll">
			<div style="width: 20%;height:80px;float: left;margin-right:6%;"><img src="./egg/img/160615zadan/iphone7.jpg" width="100%" height="100%"><br><font size="3px" color="white"><center>iphone7</center></font></div>
			<div style="width: 20%;height:80px;float: left;margin-right:6%;"><img src="./egg/img/160615zadan/huaweip9.jpg" height="100%" width="100%"><br><font size="3px" color="white"><center>华为P9</center></font></div>
			<div style="width: 20%;height:80px;float: left;margin-right:6%;"><img src="./egg/img/160615zadan/xiaomidianyuan.jpg" width="100%" height="100%"><br><font size="3px" color="white"><center>小米移动电源</center></font></div>
			<div style="width: 20%;height:80px;float: left;"><img src="./egg/img/160615zadan/money.jpg" width="100%" height="100%"><br><font size="3px" color="white"><center>随机金币</center></font></div>
			</marquee >
		</div>
	</div>

</section>
<!--弹出层-->
<article id="tip" style="margin-top:-40%;">
	<div class="pack pack_yl">
		<h1 class="tc">温馨提示</h1>
		<p class="tc"></p>
		<a href="" onclick="javascript:window.location.reload()">确定</a>
	</div>
</article>
<script type="text/javascript">
	$(function() {
		$(window).on("load", function() {
				$("#loading").fadeOut();
			})
			// ========================================浮层控制
		$("#tip .pack a").on("click", function() {
			$("#tip").fadeOut()
			$("#tip .pack p").html("")
			return false;
		})
		function alerths(str) {
			$("#tip").fadeIn()
			$("#tip .pack p").html(str)
			return false;
		}
		$("#submit").on("click", function() {
			//验证用户金币数和次数
			var draw_count = "<?php echo $count;?>";
			if(draw_count  < 1){
				alerths('今天的抽奖机会已经用完了，下次再来吧！');
				return false;
			}
			var money = "<?php echo $user['money'];?>";
			if(money < 200000){
				alerths('无法抽奖，金币不足');
				return false;
			}
			//alerths(money);
			//可以进行抽奖
			$.ajax({
				url:"./egg/data.php",
				data:{userid:"<?php echo $user['userID'];?>",},
				type:'GET',
				success:function(data){
					console.log(data);
					$('html,body').animate({scrollTop:0}); 
					$(".zd_jindan").addClass("zd_jindan01");
					$(".cardai01").fadeIn(500);
					$(".cardai02").animate({
						top: '240px',
						left: '173px'
					}, 500);
					$(".cardai03").animate({
						top: '225px',
						left: '345px'
					}, 600);
					$(".cardai04").animate({
						top: '230px',
						left: '202px'
					}, 700);
					$(".cardai05").animate({
						top: '230px',
						left: '230px'
					}, 800);
					$(".cardai06").animate({
						top: '340px',
						left: '246px'
					}, 1000, function() {
						//写中奖后的东西
						$(".cardai").addClass("cardai07");
						$(".zd_jindan").removeClass("zd_jindan01");
						alerths(data);
					});
					return false;
					}
			});
			return false;
		})
	})
</script>

</body>
</html>
