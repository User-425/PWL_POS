<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // $data = [
        //     'username' => 'customer-1',
        //     'nama' => 'Pelanggan',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 4
        // ];
        // UserModel::insert($data);

        // $data = ['nama' => 'Pelanggan Pertama'];
        // UserModel::where('username', 'customer-1')->update($data);

        // Jobsheet 4, Praktikum 1.2
        // ---------------------------

        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];
        // UserModel::create($data);

        // $user = UserModel::all();
        // return view('user', ['data' => $user]);

        // Jobsheet 4, Praktikum 2.1.1
        // ---------------------------

        // $user = UserModel::find(1);
        // return view('user', ['data' => $user]);

        // Jobsheet 4, Praktikum 2.1.4
        // ---------------------------

        // $user = UserModel::where('level_id',1)->first();
        // return view('user', ['data' => $user]);

        // Jobsheet 4, Praktikum 2.1.6
        // ---------------------------

        // $user = UserModel::firstWhere('level_id',1);
        // return view('user', ['data' => $user]);

        // Jobsheet 4, Praktikum 2.1.8
        // ---------------------------

        // $user = UserModel::findOr(1, ['username' , 'nama'], function(){
        //     abort(404);
        // });
        // return view('user', ['data' => $user]);

        // Jobsheet 4, Praktikum 2.1.10
        // ---------------------------

         $user = UserModel::findOr(20, ['username' , 'nama'], function(){
            abort(404);
        });
        return view('user', ['data' => $user]);
    }
}
