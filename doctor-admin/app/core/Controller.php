<?php
class Controller
{
    public function view($file, $data = [])
    {
        extract($data);
        require __DIR__ . "/../views/" . $file . ".php";
    }
}