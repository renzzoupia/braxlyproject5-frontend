<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function vista()
    {
        return view('admin_template/admin_html');
    }
}
