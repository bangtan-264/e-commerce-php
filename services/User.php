<?php

declare(strict_types=1);

namespace App;

require_once "db_operations/Database.php";

interface User
{
    public function displaySignup();

    public function displayLogin();

    public function signup($data);

    public function login($data);
    
    public function logout();

    public function displayResetPassword();
}
