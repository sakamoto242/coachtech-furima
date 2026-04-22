@extends('layouts.app')

@section('content')
<style>
    /* 全体レイアウト */
    .purchase-container {
        max-width: 1000px;
        margin: 40px auto;
        display: flex;
        gap: 50px;
        padding: 0 20px;
        align-items: flex-start;
    }

    /* 左側：商品情報・支払い方法・配送先 */
    .purchase-left {
        flex: 1.5;
    }

    .product-info-mini {
        display: flex;
        gap: 30px;
        margin-bottom: 40px;
    }
    .product-info-mini img {
        width: 150px;
        height: auto;
        object-fit: cover;
    }
    .product-info-mini .details h2 {
    font-size: 28px; 
    margin-bottom: 8px;
}
.product-info-mini .details p {
    font-size: 22px; 
}

    .purchase-section {
        margin-bottom: 50px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 30px;
    }
    .purchase-section h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .purchase-section select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .address-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .btn-change-address {
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
    }

    /* 右側：決済合計ボックス */
    .purchase-right {
        flex: 1;
        border: 1px solid #ddd;
        padding: 30px;
        border-radius: 4px;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    .summary-table tr th, .summary-table tr td {
        padding: 15px 0;
        text-align: left;
    }
    .summary-table tr {
    border-bottom: 1px solid #eee; 
}
.summary-table tr:last-child {
    border-bottom: none; 
}
    .summary-table tr th { font-weight: normal; color: #333; }
    .summary-table tr td { text-align: right; font-weight: bold; }

    /* 支払い方法未選択時の赤文字 */
    .method-warning {
        color: #ff5a5f;
        font-weight: bold;
    }

    /* 購入ボタン */
    .btn-submit-purchase {
        background-color: #ff5a5f;
        color: white;
        border: none;
        width: 100%;
        padding: 15px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 18px;
        cursor: pointer;
    }
</style>

<div class="purchase-container">
    <div class="purchase-left">
        {{-- 商品情報 --}}
        <div class="product-info-mini">
            <img src="{{ asset('storage/' . $product->image_url) }}" alt="">
            <div class="details">
                <h2>{{ $product->name }}</h2>
                <p>¥{{ number_format($product->price) }}</p>
            </div>
        </div>

        <form action="{{ route('purchase.store', $product->id) }}" method="POST" id="purchase-form">
            @csrf
            {{-- 支払い方法 --}}
            <div class="purchase-section">
                <h3>支払い方法</h3>
                <select name="payment_method" id="payment_method">
                    <option value="" disabled selected>選択してください</option>
                    <option value="konbini">コンビニ払い</option>
                    <option value="card">カード払い</option>
                </select>
            </div>

            {{-- 配送先 --}}
            <div class="purchase-section">
                <div class="address-header">
                    <h3>配送先</h3>
                    <a href="{{ route('address.edit', $product->id) }}" class="btn-change-address">変更する</a>
                </div>
                <p>〒 {{ Auth::user()->post_code }}</p>
                <p>{{ Auth::user()->address }}</p>
                <p>{{ Auth::user()->building }}</p>
            </div>
        </form>
    </div>

    {{-- 右側：確認ボックス --}}
    <div class="purchase-right">
        <table class="summary-table">
            <tr>
                <th>商品代金</th>
                <td>¥{{ number_format($product->price) }}</td>
            </tr>
            <tr>
                <th>支払い金額</th>
                <td>¥{{ number_format($product->price) }}</td>
            </tr>
            <tr>
                <th>支払い方法</th>
                <td>
                    <span id="selected-method" class="method-warning">選択してください</span>
                </td>
            </tr>
        </table>
        
        {{-- 右側のボタン部分 --}}
<button type="button" id="submit-btn" class="btn-submit-purchase">購入する</button>
    </div>
</div>

<script>
    const paymentSelect = document.getElementById('payment_method');
    const methodDisplay = document.getElementById('selected-method');
    const purchaseForm = document.getElementById('purchase-form');
    const submitBtn = document.getElementById('submit-btn');

    // 表示更新
    paymentSelect.addEventListener('change', function() {
        methodDisplay.textContent = this.options[this.selectedIndex].text;
        methodDisplay.classList.remove('method-warning');
        methodDisplay.style.color = '#333';
    });

    // 強制送信
    submitBtn.addEventListener('click', function() {
        if (paymentSelect.value === "") {
            alert('支払い方法を選択してください');
            return;
        }
        purchaseForm.submit();
    });
</script>
@endsection