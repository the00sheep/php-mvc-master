<?php

namespace App\Controllers;
use \App\Models\User;
use \Core\View;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function validateEmail()
    {
        $is_valid = !User::emailExists($_GET['email']);

        header('Content-Type: aplication/json');
        echo json_encode($is_valid);
    }
}
