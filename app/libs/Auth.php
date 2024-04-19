<?php
class Auth
{
    static function isLoggedIn()
    {
        return isset($_SESSION["username"]);
    }
    static function isAdmin()
    {
        return (isset($_SESSION["username"]) && $_SESSION["role"] == 'ADMIN');
    }
    static function isUser()
    {
        return (isset($_SESSION["username"]) && $_SESSION["role"] == 'USER');
    }

}
