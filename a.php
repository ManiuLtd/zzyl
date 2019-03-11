<?php

$workpath = '/root/demo/';
$gitpath = 'git@gitee.com:584096830/api.git';
shell_exec("cd {$workpath} && git pull {$gitPath}");
