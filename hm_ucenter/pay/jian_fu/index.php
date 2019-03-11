<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>支付页面</title>
</head>
<body>
<form id="myform" method="post" action="https://ce.jf0755.com/Pay_Index.html">
    <?php
    foreach ($_REQUEST as $key => $val) {
        echo '<input type="hidden" name="' . $key . '" value="' . $val . '">'."\n";
    }
    ?>
</form>
</body>
<script>
    document.getElementById('myform').submit();
</script>
</html>