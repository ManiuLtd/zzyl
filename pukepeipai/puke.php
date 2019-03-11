<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<title></title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
	<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<style type="text/css">
		.players .media { display: table; }
		.players img, .cardlist img { width: 40px; cursor: pointer; margin: 2px; }
		
		.menpais .media { display: table; }
		.menpais img, .cardlist img { width: 33px; cursor: pointer; margin: 2px; }
		
		.container{width:830px;}
		.containerother{width:500px; height:500px;  background:#EEEEEE; color:#000}
		
	</style>
</head>
<body>
<table width="80%" align="center">
<tr>
<th>
<div class="container">
	<div class="row well page-header">
		<div class="col-xs-2">
			<select class="form-control players-count" id="playernum">
				<option value="3" >3 人</option>
				<option value="4" >4 人</option>
				<option value="5" >5 人</option>
				<option value="6" >6 人</option>
				<option value="7" >7 人</option>
				<option value="8" selected="selected">8 人</option>
			</select>
		</div>
		
		<div class="col-xs-2" style="width:180px">
			<select class="form-control banker" id="banker"style="width:150px">
				<option value="0" selected="selected">按系统选择庄家</option>
				<option value="1">玩家 1 坐庄</option>
				<option value="2">玩家 2 坐庄</option>
				<option value="3">玩家 3 坐庄</option>
				<option value="4">玩家 4 坐庄</option>
				<option value="1">玩家 5 坐庄</option>
				<option value="2">玩家 6 坐庄</option>
				<option value="3">玩家 7 坐庄</option>
				<option value="4">玩家 8 坐庄</option>
			</select>
		</div>
		
		<button type="button" class="btn btn-primary post">提 交</button>
		<button type="button" class="btn btn-warning random">随机配手牌</button>
        <button type="button" class="btn btn-primary cancel">取消配牌</button>
		<button type="button" class="btn btn-primary recover">恢复配牌</button>
		文件名：<input type="text" value="11111" id="filename" style="width:80px"/>		
	</div>
	<div class="alert alert-info">当前发牌对象：玩家 <strong class="label label-danger current-player">1</strong></div>
	<div class="row jumbotron players">
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=1>玩家 1</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=2>玩家 2</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=3>玩家 3</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=4>玩家 4</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=5>玩家 5</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=6>玩家 6</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=7>玩家 7</a></h4>
			<div class="media-body player-cards"></div>
		</div>
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-playerid=8>玩家 8</a></h4>
			<div class="media-body player-cards"></div>
		</div>
	</div>
	
	<div class="alert alert-info">当前发牌对象：底牌<strong class="label label-danger current-menpai">1</strong></div>
	<div class="row jumbotron menpais">
		<div class="media">
			<h4 class="media-middle pull-left"><a class="btn btn-info" data-menpaiid=1>底牌</a></h4>
			<div class="media-body menpai-cards"></div>
		</div>	
	</div>

</div>
</th>
<th>
<div class="containerother">
	<div align="center" class="row">
		<h4>所有牌型</h4><hr>
		<div class="cardlist"></div>
	</div>
</div>
</th>
</tr>
</table>


<script type="text/javascript">

//window.onbeforeunload = function() { 
//document.getElementById('filename').value = "11111";
//}

$(function(){

	$.fn.extend({

		cardsInit: function(options) {
			var defaults = {
				//server定义的牌值（对应牌的图片也要以此值命名）
				cardsConf: [
					1, 2, 3, 4, 5, 6, 7, 8, 9,10, 11,12,13,	//方块
					17,18,19,20,21,22,23,24,25,26,27,28,29,	//梅花
					33,34,35,36,37,38,39,40,41,42,43,44,45,	//红桃
					49,50,51,52,53,54,55,56,57,58,59,60,61,	//黑桃
					78,79								//王
				],
				imgPath: 'puke/',
				cardLimitTimes: 1,
				xMaxCount: 5, //玩家可配张数
				postUrl: '#',
			}

			var opt = $.extend(defaults, options);
			var _this = $(this);
			var cards_used = new Array(); //单张牌已使用次数
			var menpaiid = 0;
			
			//显示所有牌型
			$.each(opt.cardsConf, function(i, n){
				var objImg = $('<img>').attr({ src: opt.imgPath + n + '.png', id: n });
				_this.append(objImg);
				if ($.inArray(n, [13,29,45,61]) != -1) { //换行判断
					_this.append($('<br/>'));
				}
				cards_used[n] = 0; //初始化
			})

			//选中当前配牌玩家
			$('a[data-playerid]').on('click', function(){
				
				//置门牌的选择为无效值
				$('.current-menpai').text("");
				$('a[data-menpaiid]').removeClass('btn-info').addClass('btn-success')
					.parents('.media').siblings().find('a').removeClass('btn-success').addClass('btn-info');
				
				var playerid = $(this).data('playerid');
				$('.current-player').text(playerid);

				$(this).removeClass('btn-info').addClass('btn-success')
					.parents('.media').siblings().find('a').removeClass('btn-success').addClass('btn-info');
			})
			.eq(0).click();
			
			//选中当前配的门牌
			$('a[data-menpaiid]').on('click', function(){
				
				//置配牌玩家的选择为无效值
				$('.current-player').text(0);
				$('a[data-playerid]').removeClass('btn-info').addClass('btn-success')
					.parents('.media').siblings().find('a').removeClass('btn-success').addClass('btn-info');
				
				
				menpaiid = $(this).data('menpaiid');
				if(1 == menpaiid)
				{
					$('.current-menpai').text("底");
				}
				
				$(this).removeClass('btn-info').addClass('btn-success')
					.parents('.media').siblings().find('a').removeClass('btn-success').addClass('btn-info');
			})
			//.eq(0).click();


			//选择人数
			$('.players-count').on('change', function(){
				var players = $(this).val();
				
				$('.player-sel').text(players);
				console.log(players);
				var aaaa = $('.player-select').text();
				//console.log(aaaa);
				
				$.each($('.players div.media'), function(i, o){ //玩家联动
					(i+1 > players) ? $(o).hide() : $(o).show();
				})
				
				$.each($('.menpais div.media'), function(o){ //门牌显示
					$(o).show();
				})
				
				$.each($('.banker option'), function(i, o){ //庄家联动
					(i > players) ? $(o).hide() : $(o).show();
				})
				
				$('a[data-playerid]').eq(0).click(); //当前配牌玩家重置
			})

			//配牌add
			$('.cardlist img').on('click', function(){
				
				if (cards_used[this.id] >= opt.cardLimitTimes) { return false; } //单张可用次数限制
					
				if ( this.id >= 78 ) {return false;} 							//大小王不可以选
				//玩家手牌
				if(0 != $('.current-player').text())
				{
					var n = $('.current-player').text();
					var m = $('.player-cards').eq(n-1).find('img').length + 1; //当前玩家已配张数
					if (m > opt.xMaxCount) { return false; }
					

					$(this).clone().appendTo($('.player-cards').eq(n-1));
					if (++cards_used[this.id] >= opt.cardLimitTimes) {
						$(this).attr({ 'src': opt.imgPath + 'back.png', 'src-bak': this.src });
					}
				}

				//底牌
				if(0 != $('.current-menpai').text())
				{
					var n = menpaiid;
					var limit = 10;
					var m = $('.menpai-cards').eq(n-1).find('img').length + 1; //当前玩家已配张数
					if (m > limit) { return false; }

					$(this).clone().appendTo($('.menpai-cards').eq(n-1));
					if (++cards_used[this.id] >= opt.cardLimitTimes) {
						$(this).attr({ 'src': opt.imgPath + 'back.png', 'src-bak': this.src });
					}
				}
			})

			//配牌del
			$('.player-cards').on('click', 'img', function(){
				$(this).remove();
				if (--cards_used[this.id] < opt.cardLimitTimes) {
					$('.cardlist').find('[id="'+this.id+'"]').attr('src', this.src).removeAttr('src-bak');
				}
			})
			//门牌del
			$('.menpai-cards').on('click', 'img', function(){
				$(this).remove();
				if (--cards_used[this.id] < opt.cardLimitTimes) {
					$('.cardlist').find('[id="'+this.id+'"]').attr('src', this.src).removeAttr('src-bak');
				}
			})

			//随机配牌
			$('.random').on('click', function(){
				
				var options = $("#playernum option:selected");
				var playersCount = options.val();//获取中的游戏人数
				console.log(playersCount);
				
				$.each($('.player-cards'), function(j, o){
					if(j >= playersCount){return false;}
					$('a[data-playerid]').eq(j).click(); //切换选中
					$('.current-player').text(j+1);

					$(o).find('img').click(); //清除之前配置（若要保留部分手动配牌，剩余随机时，请注释该行）
					
					var m = $(o).find('img').length; //当前玩家已配张数
					var l = opt.xMaxCount - m;
					for (var i = 0; i < l; i++) {
						var d = Math.ceil(Math.random() * opt.cardsConf.length);
						while($.inArray(d, opt.cardsConf) == -1 || cards_used[d] >= opt.cardLimitTimes) { //过滤无效随机数
							d = Math.ceil(Math.random() * opt.cardsConf.length);
						}
						$('.cardlist img[id="'+d+'"]').click(); //发牌
					}
				})
			})

			//提交并生成配置文件
			$('.post').on('click', function(){
				var filename = document.getElementById("filename").value+".json";
				var count = 0;
				var postCards = new Array();
				$.each($('.players div.media:visible'), function(i, o){
					postCards[i] = [];
					postCards[i][0] = 0;
					$.each($(o).find('img'), function(j, t){
						postCards[i][j] = t.id;
						count+=1;
					})
				})
				
				var mCardData = new Array();
				$.each($('.menpais div.media:visible'), function(i, o){
					mCardData[i] = [];
					mCardData[i][0] = 0;
					$.each($(o).find('img'), function(j, t){
						mCardData[i][j] = t.id;
						count+=1;
					})
				})
				
				var options = $("#banker option:selected");
				var whoisbanker = options.val();//获取庄家
				
				if(count == 0)
				{
					alert('手牌和门牌均为空，不写入文件！');
				}
				else
				{
					$.post(opt.postUrl, {status:1,data:postCards,mdata:mCardData,banker:whoisbanker,name:filename}, function(r){
					//alert(r==1?'JSON配置文件写入成功':'操作失败');
						alert('JSON配置文件写入成功');
					});
				}
			})
			
			$('.cancel').on('click',function(){
				var filename = document.getElementById("filename").value+".json";
				$.post(opt.postUrl, {status:0,cancel:1,name:filename}, function(r){
					//alert(r==1?'JSON配置文件写入成功':'操作失败');
					alert('取消配牌成功');
				});
			});
			
			$('.recover').on('click',function(){
				var filename = document.getElementById("filename").value+".json";
				$.post(opt.postUrl, {status:1,recover:1,name:filename}, function(r){
					alert('恢复配牌成功');
				});
			});
		}
	});
	$('.cardlist').cardsInit(); //初始化
})
</script>

</body>

<?php
ini_set("display_errors","Off");
$dir = "C:/web/json/"; //配置json文件存放目录
$filename = $_POST['name'];

	$config = [];
	
	$status = intval($_POST['status']);
	$config['status'] = $status;
	
	$banker = intval($_POST['banker']);
	$config['first'] = $banker;

	if ($_POST['data']) 
	{
		$data = $_POST['data'];
	    foreach ($data as $k => $v) {
			
			if($data[$k][0] == 0)
			{
				$config['CardCout'][] = ["p{$k}" => 0];
				continue;
			}
			
	        $config['CardCout'][] = ["p{$k}" => count($v)];
	        foreach ($v as $kk => $vv) {
	            $config['CardData']["p{$k}"][] = ["n{$kk}" => intval($vv)];
	        }
	    }
	}
	
	if ($_POST['mdata']) 
	{
		$mdata = $_POST['mdata'];
	    foreach ($mdata as $mk => $mv) {
			
			if($mdata[$mk][0] == 0)
			{
				$config['MCardCout'][] = ["p{$mk}" => 0];
				continue;
			}
			
	        $config['MCardCout'][] = ["p{$mk}" => count($mv)];
	        foreach ($mv as $mkk => $mvv) {
	            $config['MCardData']["p{$mk}"][] = ["n{$mkk}" => intval($mvv)];
	        }
	    }
	}

	if($_POST['data'] || $_POST['mdata'])
	{
		$config = json_encode($config);
		$strconf = str_replace(['p','n'], '', $config);

		is_dir($dir) || mkdir($dir, 0775, true);
		$flag = file_put_contents($dir.$filename, $strconf, LOCK_EX);
		var_dump($flag);
		return $flag === false ? -1 : 1;
	}

	if($_POST['cancel']){
		$status = intval($_POST['status']);	
		if(file_exists($dir.$filename))
		{
			$json = file_get_contents($dir.$filename);
			$json = str_replace('"status":1', '"status":0', $json);
			file_put_contents($dir.$filename,$json);
		}
	}
	
	if($_POST['recover']){
		$status = intval($_POST['status']);
		if(file_exists($dir.$filename))
		{
			$json = file_get_contents($dir.$filename);
			$json = str_replace('"status":0', '"status":1', $json);
			file_put_contents($dir.$filename,$json);
		}
	}
?>

</html>