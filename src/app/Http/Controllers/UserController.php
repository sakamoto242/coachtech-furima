<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mypage(Request $request)
    {
        $user = Auth::user();
        
    
        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
      
            $products = Product::where('buyer_id', $user->id)->get();
        } else {
           
            $products = Product::where('user_id', $user->id)->get();
        }

     
        return view('mypage', compact('user', 'products', 'tab'));
    }
    public function update(Request $request)
{
   
    $request->validate([
        'name' => 'required|string|max:255',
        'post_code' => 'required|digits:7', 
        'address' => 'required|string|max:255',
        'building_name' => 'nullable|string|max:255',
    ], [
     
        'post_code.digits' => '郵便番号はハイフンなしの7桁で入力してください',
    ]);

  
}
}