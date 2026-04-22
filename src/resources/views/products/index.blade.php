@extends('layouts.app')

@section('content')
<style>

    .products-container {
        max-width: 1024px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .tab-menu-wrapper {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        border-bottom: 1px solid #ddd; 
        margin-bottom: 40px;
    }

    .tab-menu {
        display: flex;
        gap: 50px;
        max-width: 1024px;
        margin: 0 auto;
        padding: 0 50px;
    }

    .tab-item {
        text-decoration: none;
        padding-bottom: 15px;
        font-weight: bold;
        font-size: 16px;
        color: #888;
        border-bottom: 3px solid transparent;
        margin-bottom: -1px; 
    }

    .tab-item.active {
        color: #ff5a5f;
        border-bottom: 3px solid #ff5a5f;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }

    .product-image-box {
        position: relative;
        aspect-ratio: 1 / 1;
        background-color: #d9d9d9;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .product-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-card a {
    text-decoration: none !important; 
    color: #333 !important;         
}

    .product-name {
        font-size: 15px;
    margin-top: 10px;
    font-weight: 500;
    }
    /* Soldラベルのデザイン */
.sold-label {
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(255, 0, 0, 0.8); /* 赤い半透明 */
    color: white;
    padding: 5px 15px;
    font-weight: bold;
    font-size: 14px;
    z-index: 10;
}
</style>

<div class="products-container">
    {{-- タブメニュー --}}
<div class="tab-menu-wrapper">
    <div class="tab-menu">
        <a href="{{ route('product.index', ['keyword' => request('keyword')]) }}" 
           class="tab-item {{ !request('page') ? 'active' : '' }}">
           おすすめ
        </a>
        
     
        <a href="{{ route('product.index', ['page' => 'mylist', 'keyword' => request('keyword')]) }}" 
           class="tab-item {{ request('page') == 'mylist' ? 'active' : '' }}">
           マイリスト
        </a>
    </div>
</div>

    {{-- 商品グリッド --}}
    <div class="product-grid">
        @forelse($products as $product)
    <div class="product-card">
        <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit;">
            <div class="product-image-box">
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}">
                @else
                    <span style="color: #888;">No Image</span>
                @endif

                @if($product->buyer_id)
                    <div class="sold-label">Sold</div>
                @endif
            </div>
            <p class="product-name">{{ $product->name }}</p>
        </a>
    </div>
@empty
            <p style="grid-column: 1 / -1; text-align: center; color: #888; padding: 100px 0;">商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection