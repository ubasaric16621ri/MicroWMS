<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $except = [
    'inventory/in'
];

}
