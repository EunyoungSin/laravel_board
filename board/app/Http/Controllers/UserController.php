<?php
/******************************************
 * Project Name : laravel_board
 * Directory    : Controllers
 * File Name    : UserController.php
 * History      : v001 0530 EY.Sin new
 *******************************************/


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function login() {
        return view('login');
    }
}
