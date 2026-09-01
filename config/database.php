<?php

function db_connect()
{
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$db) {
        die('Database connection failed: ' . mysqli_connect_error());
    }

    mysqli_set_charset($db, 'utf8mb4');

    return $db;
}