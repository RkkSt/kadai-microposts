<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Micropost;

class SearchController extends Controller
{
    public function Search(Request $request) {
        $query = Micropost::query();
        // 入力された項目を取得
        $content = $request->input('content');
        // 部分一致のみを結果に表示させる
        if ($content != '') {
            // 入力されたフォームのうち部分一致しているmicropostsを表示
            $query->where('content', 'like', '%'. $content. '%' )->get()->count();
            // 検索結果を降順、1ページ20件で表示
            $microposts = $query->orderBy('created_at', 'desc')->paginate(20);
            return view('users.search', ['microposts' => $microposts]);
        } else {
            return back();
        }
    }
}
