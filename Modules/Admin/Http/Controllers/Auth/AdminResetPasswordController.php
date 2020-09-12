<?php

namespace Modules\Admin\Http\Controllers\Auth;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AdminResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware("admin:admin");
    }

    protected function guard()
    {
        return Auth::guard("admin");
    }

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    /**
     * Show the password reset form.
     *
     * @param Request $request
     * @param $token
     * @return Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token)
    {
        if(DB::table('password_resets')->where('token', $token)->first()) {

            return view('admin::auth.reset', [
                'title' => 'Admin Password Reset',
            ]);
        }
        else
        {
            $notify["title"]="Following errors occurred.";
            $notify["status"]="failed";
            $notify["notify"][]="Your password reset request has been expired.";
            $notify["notify"][]="Please try resetting your password again.";

            $request->session()->flash("notify", $notify);
            return redirect(route("dashboard.password.request"));
        }
    }
}
