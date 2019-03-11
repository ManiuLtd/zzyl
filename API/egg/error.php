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
<link href="css/publi.css" rel="stylesheet" type="text/css">
<link href="css/chuyouwuyi01.css" rel="stylesheet" type="text/css">
<script src="js/jquery1.8.3.min.js"></script>
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
</head>
<body>
<!--弹出层-->
<article id="tip" style="margin-top:-40%;">
	<div class="pack pack_yl">
		<h1 class="tc">温馨提示</h1>
		<p class="tc"></p>
		<a href="#">确定</a>
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
		alerths("<?php echo $_GET['error'] ?>");
	})
</script>

</body>
</html>
