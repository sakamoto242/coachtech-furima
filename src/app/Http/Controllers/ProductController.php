<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

class ProductController extends Controller
{
    /**
     * 商品一覧画面 (FN014)
     */
   public function index(Request $request)
{
    $page = $request->query('page');
    $keyword = $request->query('keyword');

    $query = Product::query();

    // 【検索機能】キーワード保持のため query を維持
   if (!empty($keyword)) {
    // description への orWhere を削除し、name だけを検索対象にする
    $query->where('name', 'like', "%{$keyword}%");

    }

    if ($page === 'mylist') {
        // 【マイリスト一覧取得】未認証の場合は何も表示しない
        if (!Auth::check()) {
            return view('products.index', ['products' => collect(), 'page' => $page, 'keyword' => $keyword]);
        }
        
        $query->whereHas('likes', function ($q) {
            $q->where('user_id', Auth::id());
        });
    } else {
        // 【商品一覧取得】自分が出品した商品は表示されない
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
    }

    $products = $query->get();

    return view('products.index', compact('products', 'page', 'keyword'));
}

    /**
     * 商品詳細画面 (FN017)
     */
    public function show($item_id)
{
    $product = Product::findOrFail($item_id);
    
    // ログイン済みの場合、出品者本人なら「自分が出品した商品です」という
    // 判定ができるよう、このままビューに渡します。
    // (Blade側で Auth::id() === $product->user_id の判定を行います)
    
    return view('products.show', compact('product'));
}

    /**
     * 出品画面の表示 (FN028)
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * 商品出品処理 (FN028, FN029, FN030)
     */
    public function store(Request $request)
    {
        // 要件定義に合わせたバリデーション（US030準拠）
        $request->validate([
            'name'        => 'required',
            'description' => 'required|max:255',
            'image'       => 'required|mimes:jpeg,png',
            'categories'  => 'required',
            'condition'   => 'required',
            'price'       => 'required|integer|min:0',
        ], [
            'name.required'        => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max'      => '商品の説明は255文字以内で入力してください',
            'image.required'       => '商品画像を入力してください', // 要件に合わせ「入力」に変更
            'image.mimes'          => '商品画像はjpeg、png形式でアップロードしてください',
            'categories.required'  => 'カテゴリーを選択してください',
            'condition.required'   => '商品の状態を選択してください',
            'price.required'       => '販売価格を入力してください',
            'price.integer'        => '数値で入力してください', // 要件に合わせ修正
            'price.min'            => '0円以上で入力してください', // 要件に合わせ修正
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        // 商品作成
        $product = Product::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'brand'       => $request->brand,
            'price'       => $request->price,
            'description' => $request->description,
            'condition'   => $request->condition,
            'image_url'   => $imagePath,
            'category'    => '',
            // 'category' カラムがDBにある場合は残し、中間テーブルのみなら削除してください
        ]);

        // 中間テーブルへの保存（多対多）
        $product->categories()->attach($request->categories);

        return redirect()->route('product.index')->with('message', '出品が完了しました！');
    }

    /**
     * 送付先住所の更新処理 (FN024)
     */
   /**
     * 送付先住所の更新処理 (FN024)
     */
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'post_code' => ['required', 'digits:7'], 
            'address'   => ['required', 'string', 'max:255'],
            'building'  => ['nullable', 'string', 'max:255'],
        ], [
            'post_code.digits' => '郵便番号はハイフンなしの7桁で入力してください',
        ]);

        $user = Auth::user();

      
        $user->update([
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
        ]);

        return redirect()->route('purchase.show', ['id' => $id])
                         ->with('message', '住所を更新しました');
    }
   public function executePurchase(Request $request, $id)
{
    $request->validate([
        'payment_method' => 'required',
    ], [
        'payment_method.required' => '支払い方法を選択してください',
    ]);

    $product = Product::findOrFail($id);

    if ($product->buyer_id) {
        return redirect()->back()->with('error', 'この商品はすでに売り切れています。');
    }

    Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    $session = Session::create([
        'payment_method_types' => [$request->payment_method === 'card' ? 'card' : 'konbini'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $product->name,
                ],
                'unit_amount' => $product->price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('purchase.success', ['id' => $product->id]),
        'cancel_url' => route('purchase.show', ['id' => $product->id]),
        'metadata' => [
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
        ],
    ]); 

    return redirect($session->url, 303);
}

    /**
     * 決済成功時の処理
     */
    public function success($id)
{
    $product = Product::findOrFail($id);
    $product->update(['buyer_id' => Auth::id()]);

  
    return view('products.thanks', compact('product'));
}

/**
 * 購入確認画面の表示 
 */
public function purchase($id)
{
    $product = Product::findOrFail($id);


    if (Auth::id() === $product->user_id) {
        return redirect()->route('product.show', $product->id)
                         ->with('error', '自分が出品した商品は購入できません。');
    }

    if ($product->buyer_id) {
        return redirect()->route('product.show', $product->id)
                         ->with('error', 'この商品は既に売り切れています。');
    }

    return view('products.purchase', compact('product'));
}
/**
 * 送付先住所変更画面の表示 
 */
public function editAddress($id)
{
    
    $product = Product::findOrFail($id);
    
    $user = Auth::user();

    return view('products.address', compact('product', 'user'));
}
public function handleWebhook(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    $payload = $request->getContent();
    $data = json_decode($payload, true);

    if (!$data) {
        return response()->json(['error' => 'Invalid payload'], 400);
    }

   
    if ($data['type'] === 'checkout.session.completed') {
        $session = $data['data']['object'];

      
        $productId = $session['metadata']['product_id'] ?? null;
        $userId = $session['metadata']['user_id'] ?? null;

        if ($productId && $userId) {
            $product = Product::find($productId);
            if ($product) {
                
                $product->update(['buyer_id' => $userId]);
            }
        }
    }

    return response()->json(['status' => 'success']);
}
}