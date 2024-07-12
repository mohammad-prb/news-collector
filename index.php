<?php
require("code/db-conection.php");
require("code/jdf.php");

if (isset($_POST["delete"])) {
    $con->query("truncate news");
}
elseif (isset($_POST["submit"])) {
    $ebaratJostojoo = $_POST["search"];
    require("code/update-news.php");
}
?>
<!DOCTYPE HTML>
<html lang="fa">
<head>
    <title>گرفتن اخبار</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body dir="rtl">

<form class="kadrModiriat" action="index.php" method="post">
    <div class="kadrInput">
        <input type="text" class="search" name="search" placeholder="کلمه مورد نظر را وارد کنید"/>
        <input type="submit" class="submit" name="submit" value=""/>
    </div>
    <input type="submit" class="delete" name="delete" value="خالی کردن دیتابیس"/>
</form>

<div class="kadrAkhbar">
    <div class="kadrItemAkhbar">
        <?php
        $result = $con->query("SELECT * FROM news ORDER BY id desc limit 50");
        if ($result !== false && $result->num_rows > 0)
            while ($row = $result->fetch_assoc())
                include "code/news-item.php";
        ?>
    </div>
</div>

</body>
</html>
<?php
$con->close();
