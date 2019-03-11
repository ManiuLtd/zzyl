<?php 
$sql = "select top 1 * from web_notice where type=1 order by id";
$notice = $db -> getRow($sql);
if(!$notice){
$data=[
	'status'	=>	0,
];
exit(return_json($data));
}
$data = [
	'status'	=>	1,
	'get_new_special_notice'	=>	$notice['content'],
];
exit(return_json($data));
 ?>
