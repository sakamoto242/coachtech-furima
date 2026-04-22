<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProfileController extends Controller
{
    // 編集画面を表示する
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user')); 
    }

    // データを更新（保存）する
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'post_code' => ['required', 'digits:7'],
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
       ], [
           
            'post_code.digits' => '郵便番号はハイフンなしの7桁で入力してください',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
            $user->image = $path; 
        }

        $user->name = $request->name;
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building = $request->building;

        $user->save();

        return redirect()->route('mypage')->with('message', 'プロフィールを更新しました');
    }
}