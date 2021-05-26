<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function PrivacyPolicy(Request $request)
    {
        return view('website.privacypolicy');
    }

    public function termsofService(Request $request)
    {
        return view('website.tos');
    }
}
