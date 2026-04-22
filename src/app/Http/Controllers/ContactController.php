<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest; 
use App\Models\Category;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    // 1. 入力画面の表示
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    // 2. 確認画面の表示
    public function confirm(ContactRequest $request)
    {
        // 全入力データを取得
        $contact = $request->only(['last_name', 'first_name', 'gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building', 'category_id', 'detail']);

        // 電話番号を結合
        $contact['tel'] = $contact['tel1'] . $contact['tel2'] . $contact['tel3'];

        // カテゴリー名を取得
        $category = Category::find($contact['category_id']);
        $contact['category_content'] = $category->content;

        return view('confirm', compact('contact'));
    }

    // 3. データベースへの保存処理
    public function store(Request $request)
    {
        
        $contact = $request->only(['last_name', 'first_name', 'gender', 'email', 'tel', 'address', 'building', 'category_id', 'detail']);
        
        // データベースに保存
        Contact::create($contact);

        // 完了画面を表示
        return view('thanks');
    }
   public function admin()
    {
        $contacts = Contact::with('category')->paginate(7);
        $categories = Category::all();
        return view('admin', compact('contacts', 'categories'));
    }

    public function search(Request $request)
    {
        // リセットボタンが押されたら一覧へ
        if ($request->has('reset')) {
            return redirect('/admin');
        }

        $query = Contact::with('category');

        // キーワード検索（名前 or メール）
        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('last_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('first_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        // 性別検索
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // お問い合わせの種類検索
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 日付検索
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->paginate(7);
        $categories = Category::all();

        return view('admin', compact('contacts', 'categories'));
    }

    // 削除機能
    public function destroy(Request $request)
    {
      $contacts = Contact::with('category')->get(); 

    $csvHeader = ['お名前', '性別', 'メールアドレス', '電話番号', '住所', '建物名', 'お問い合わせの種類', 'お問い合わせ内容'];

    $response = new StreamedResponse(function () use ($contacts, $csvHeader) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $csvHeader);

        foreach ($contacts as $contact) {
            $genders = [1 => '男性', 2 => '女性', 3 => 'その他'];
            fputcsv($handle, [
                $contact->last_name . ' ' . $contact->first_name,
                $genders[$contact->gender] ?? '',
                $contact->email,
                $contact->tel,
                $contact->address,
                $contact->building,
                $contact->category->content ?? '',
                $contact->detail,
            ]);
        }
        fclose($handle);
    }, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="contacts_' . date('YmdHis') . '.csv"',
    ]);

    return $response;
    }
    public function export(Request $request)
{
 
    $query = Contact::with('category');

   
    if ($request->has('fullname')) {
        $query->where('last_name', 'like', '%' . $request->fullname . '%')
              ->orWhere('first_name', 'like', '%' . $request->fullname . '%');
    }
    // ... 他の検索条件も必要に応じて追記 ...

    $contacts = $query->get();

    $csvHeader = ['お名前', '性別', 'メールアドレス', '電話番号', '住所', '建物名', 'お問い合わせの種類', 'お問い合わせ内容'];

    $response = new StreamedResponse(function () use ($contacts, $csvHeader) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $csvHeader);

        foreach ($contacts as $contact) {
            $genders = [1 => '男性', 2 => '女性', 3 => 'その他'];
            fputcsv($handle, [
                $contact->last_name . ' ' . $contact->first_name,
                $genders[$contact->gender] ?? '',
                $contact->email,
                $contact->tel,
                $contact->address,
                $contact->building,
                $contact->category->content ?? '', // カテゴリー名
                $contact->detail,
            ]);
        }
        fclose($handle);
    }, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="contacts_' . date('YmdHis') . '.csv"',
    ]);

    return $response;
}
}