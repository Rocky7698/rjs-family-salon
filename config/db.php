<?php
date_default_timezone_set('Asia/Kolkata');

$conn = mysqli_connect("localhost", "root", "root", "rjs_salon");

if (!$conn) {
    die("Database connection failed");
}
// Developer Secret Key (ONLY FOR YOU)
define('DEV_KEY', 'RINKESH_DEV_2025');
