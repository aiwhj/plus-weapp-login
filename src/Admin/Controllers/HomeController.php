<?php

namespace Aiwhj\WeappLogin\Admin\Controllers;

class HomeController
{
    public function index()
    {
        return trans('weapp-login::messages.success');
    }
}
