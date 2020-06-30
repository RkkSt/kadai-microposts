<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store($micropost_id)
    {
        // だれ(ログインしているユーザ)が何のつぶやきをお気に入りするか。
        \Auth::user()->favorite($micropost_id);
        return back();
    }

    public function destroy($micropost_id)
    {
        \Auth::user()->unfavorite($micropost_id);
        return back();
    }
}
