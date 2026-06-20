<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }
}
