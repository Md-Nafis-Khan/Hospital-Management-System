<?php

function get_doctors($conn)
{
    $result = mysqli_query($conn, "SELECT id, name, email FROM users WHERE role = 'doctor' ORDER BY name");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
