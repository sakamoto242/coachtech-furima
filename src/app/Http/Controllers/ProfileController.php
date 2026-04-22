<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * プロフィール設定画面（初回用）の表示
     */

public function create()
{
    $user = Auth::user();

   
    if (!empty($user->address)) {
        return redirect('/?page=mylist');
    }

    return view('profile_setup', compact('user'));
}
   
    public function store(Request $request)
{
    // バリデーション
    $request->validate([
        'image'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        'name'      => ['required', 'string', 'max:255'],
        'post_code' => ['required', 'digits:7'], 
        'address'   => ['required', 'string', 'max:255'],
        'building'  => ['nullable', 'string', 'max:255'],
    ], [
    
        'post_code.required' => '郵便番号を入力してください',
        'post_code.digits'   => '郵便番号はハイフンなしの7桁で入力してください',
    ]);

    $user = Auth::user();

    // 画像の保存処理
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('avatars', 'public');
        $user->image = $path;
    }

    // 情報の更新
    $user->name = $request->name;
    $user->post_code = $request->post_code;
    $user->address = $request->address;
    $user->building = $request->building;
    $user->save();

    return redirect()->route('product.index')->with('success', 'プロフィールを更新しました');
}
}