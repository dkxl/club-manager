<?php
/*
 * Main controller for Club GUI pages
 */
namespace App\Http\Controllers;


class MainController extends BaseController
{

    /**
     * Main landing page --> events diary
     */
    public function index()
    {
        return view('diary.index');
    }


    /**
     * Front of House
     * Self Service Check Ins etc
     */
    public function foh()
    {
        return view('foh.index');
    }


    /**
     * Club Administration
     */
    public function clubAdmin()
    {
        return view('main.admin');
    }

    /**
     * Club Administration
     */
    public function memberAdmin()
    {
        return view('main.members');
    }


}
