<?php
include("security.php");

http_send_file($_GET["f"]);
?>