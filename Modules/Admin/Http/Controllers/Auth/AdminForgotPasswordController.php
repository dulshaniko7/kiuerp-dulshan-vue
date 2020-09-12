<?php

namespace Modules\Admin\Http\Controllers\Auth;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\View;

class AdminForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware("admin:admin");
    }

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Show the password rest request form.
     *
     * @return Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('admin::auth.forgot',[
            'title' => 'Admin Password Reset Request',
            'resetRequestRoute' => 'admin.password.request',
        ]);
    }
}
