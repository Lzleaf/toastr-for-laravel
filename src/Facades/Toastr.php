<?php
/**
 * Created by PhpStorm.
 * User: leaf
 * Date: 2019/9/3
 * Time: 10:51 AM
 */

namespace Leaf\LaraFlash\Facades;


use Illuminate\Support\Facades\Facade;

class Toastr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'toastr';
    }
}