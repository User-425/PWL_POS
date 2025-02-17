<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        return 'Selamat Datang';
    }

    public function about(){
        return '2341720105 - Achmad Anfasa Rabbany';
    }

    public function articles($id){
        return 'Halaman artikel dengan ID ' . $id;
    }
}
