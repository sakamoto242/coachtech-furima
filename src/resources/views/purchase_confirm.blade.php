@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f5f5f5; color: #333; }

    .purchase-container {
        max-width: 1000px;
        margin: 40px auto;
        display: flex;
        gap: 50px;
        padding: 0 20px;
    }

    /* 左側メインセクション */
    .purchase-left { flex: 1.5; }

    .product-info-section {
        display: flex;
        gap: 25px;
        margin-bottom: 30px;
    }

    .product-image-placeholder {
        width: 150px;
        height: 150px;
        background-color: #d9d9d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .product-image-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-detail-top h2 { font-size: 22px; margin: 0 0 10px 0; }
    .product-detail-top p { font-size: 18px; margin: 0; }

    /* セクションの区切り線 */
    .border-section {
        border-top: 2px solid #555;
        padding: 20px 0 30px 0;
        position: relative;
    }

    .border-section h3 { font-size: 16px; margin-bottom: 15px; font-weight: bold; }

    /* セレクトボックス */
    .payment-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
    }

    /* 変更するリンク */
    .edit-link {
        position: absolute;
        right: 0;
        top: 20px;
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
    }

    .address-box { padding-left: 30px; line-height: 1.8; }

    /* 右側サイドバー */
    .purchase-right {
        flex: 1;
        height: fit-content;
    }

    .summary-card {
        background-color: #fff;
        border: 1px solid #ddd;
        margin-bottom: 25px;
    }

    .summary-table { width: 100%; border-collapse: collapse; }
    .summary-table th, .summary-table td {
        padding: 20px;
        text-align: left;
        border: 1px solid #ddd;
    }
    .summary-table th { font-weight: normal; width: 45%; }

    /* 購入ボタン */
    .btn-buy {
        width: 100%;
        background-color: #ff5a5f;
        color: white;
        border: none;
        padding: 14px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-buy:hover { opacity: 0.9; }

    @media (max-width: 768px) {
        .purchase-container { flex-direction: column; }
    }
</style>

<div class="purchase-container">
    {{-- 左側 --}}
    <div class="purchase-left">
        <div class="product-info-section">
            <div class="product-image-placeholder">
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="">
                @else
                    商品画像
                @endif
            </div>
            <div class="product-detail-top">
                <h2>{{ $product->name }}</h2>
                <p>¥ {{ number_format($product->price) }}</p>
            </div>
        </div>

        <div class="border-section">
            <h3>支払い方法</h3>
            <select class="payment-select">
                <option value="" selected disabled>選択してください</option>
                <option value="card">クレジットカード</option>
                <option value="konbini">コンビニ払い</option>
            </select>
        </div>

        <div class="border-section">
    <h3>配送先</h3>
    <a href="{{ route('address.edit', ['id' => $product->id]) }}" class="edit-link">変更する</a>
    <div class="address-box">
    {{-- 1. 郵便番号（〒 と データを1行にまとめ、アンダーバーありの post_code を使う） --}}
    <p>〒 {{ $user->post_code ?? '未登録' }}</p>
    
    {{-- 2. 住所 --}}
    <p>{{ $user->address ?? '住所が登録されていません' }}</p>
    
    {{-- 3. 建物名 --}}
    <p>{{ $user->building ?? '' }}</p> 
</div>
</div>
    </div>

    {{-- 右側 --}}
    <div class="purchase-right">
        <div class="summary-card">
            <table class="summary-table">
                <tr>
                    <th>商品代金</th>
                    <td>¥ {{ number_format($product->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td>コンビニ払い</td>
                </tr>
            </table>
        </div>
        
        {{-- 右側のフォーム部分 --}}
{{-- 右側のフォーム部分 --}}
<form action="{{ route('purchase.execute', ['id' => $product->id]) }}" method="POST">
    @csrf
    <input type="hidden" name="payment_method" id="payment-method-hidden" value="">
    <button type="submit" class="btn-buy">購入する</button>
</form>

<script>
    document.querySelector('.payment-select').addEventListener('change', function() {
        const val = this.value;
        const methodText = val === 'card' ? 'クレジットカード' : 'コンビニ払い';
        

        document.querySelectorAll('.summary-table td')[1].innerText = methodText;


        document.getElementById('payment-method-hidden').value = val;
    });
</script>
@endsection