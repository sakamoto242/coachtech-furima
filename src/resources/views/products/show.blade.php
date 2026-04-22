@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* 全体レイアウト */
    .show-container {
        max-width: 1000px;
        margin: 40px auto;
        display: flex;
        gap: 60px;
        padding: 0 20px;
        align-items: flex-start;
    }

    .show-image-box { flex: 1; text-align: center; }
    .show-image-box img { width: 100%; max-width: 450px; height: auto; }
    .show-details { flex: 1; }

    /* 商品タイトル・価格 */
    .product-title { font-size: 28px; font-weight: bold; margin-bottom: 5px; }
    .brand-name { color: #666; margin-bottom: 15px; }
    .price { font-size: 24px; font-weight: bold; margin-bottom: 20px; }

    /* アイコン */
    .icon-group { display: flex; gap: 30px; margin-bottom: 25px; }
    .icon-item { text-align: center; }
    .icon-item i { font-size: 28px; display: block; margin-bottom: 4px; }
    .like-btn { background: none; border: none; padding: 0; cursor: pointer; }

    /* 購入ボタン */
    .btn-purchase {
        background-color: #ff5a5f;
        color: white !important;
        text-align: center;
        padding: 12px;
        border-radius: 4px;
        display: block;
        text-decoration: none;
        font-weight: bold;
        margin-bottom: 30px;
    }

    /* セクションタイトル */
    .section-title {
        font-size: 20px;
        font-weight: bold;
        border-bottom: 1px solid #333;
        padding-bottom: 10px;
        margin: 40px 0 20px;
    }

    /* 商品情報テーブル */
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table th { width: 30%; text-align: left; padding: 15px 0; font-weight: bold; }
    .info-table td { padding: 15px 0; }

    /* カテゴリータグ */
    .category-tag {
        background: #e0e0e0;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        margin-right: 8px;
    }

    /* コメント表示 */
    .comment-item { margin-bottom: 20px; }
    .comment-user { display: flex; align-items: center; gap: 10px; margin-bottom: 5px; }
    .user-icon { width: 35px; height: 35px; background: #ddd; border-radius: 50%; }
    .comment-text { background: #eee; padding: 10px 15px; border-radius: 4px; font-size: 14px; display: inline-block; }

    /* 送信フォーム */
    .comment-form textarea { width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px; }
    .btn-submit {
        background-color: #ff5a5f;
        color: white;
        border: none;
        width: 100%;
        padding: 12px;
        border-radius: 4px;
        margin-top: 15px;
        font-weight: bold;
    }
</style>

<div class="show-container">
   
    <div class="show-image-box">
        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}">
    </div>

  
    <div class="show-details">
        <h1 class="product-title">{{ $product->name }}</h1>
        <p class="brand-name">{{ $product->brand ?? 'ブランドなし' }}</p>
        <p class="price">¥{{ number_format($product->price) }} <small>(税込)</small></p>

        <div class="icon-group">
            <div class="icon-item">
                @auth
                    <form action="{{ $product->isLikedBy(Auth::user()) ? route('like.destroy', $product->id) : route('like.store', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="like-btn">
                            <i class="{{ $product->isLikedBy(Auth::user()) ? 'fa-solid' : 'fa-regular' }} fa-heart" style="color: {{ $product->isLikedBy(Auth::user()) ? '#ff5a5f' : '#333' }};"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="like-btn"><i class="fa-regular fa-heart"></i></a>
                @endauth
                <span class="icon-count">{{ $product->likes->count() }}</span>
            </div>
            <div class="icon-item">
                <i class="fa-regular fa-comment"></i>
                <span class="icon-count">{{ $product->comments->count() }}</span>
            </div>
        </div>

       {{-- 商品が未購入 かつ ログインユーザーが出品者本人ではない場合のみ購入ボタンを表示 --}}
@if(!$product->buyer_id && Auth::id() !== $product->user_id)
    <a href="{{ route('purchase.show', $product->id) }}" class="btn-purchase">購入手続きへ</a>

@elseif($product->buyer_id)
    <button class="btn-purchase" style="background-color: #888; cursor: not-allowed;" disabled>売り切れました</button>

@else

    <button class="btn-purchase" style="background-color: #ccc; cursor: default;" disabled>自分が出品した商品です</button>
@endif

        <h2 class="section-title">商品説明</h2>
        <div class="description-text">{{ $product->description }}</div>

        <h2 class="section-title">商品の情報</h2>
        <table class="info-table">
            <tr>
                <th>カテゴリー</th>
                <td>
                    @forelse($product->categories as $category)
                        <span class="category-tag">{{ $category->content }}</span>
                    @empty
                        <span class="category-tag">洋服</span><span class="category-tag">メンズ</span>
                    @endforelse
                </td>
            </tr>
            <tr>
                <th>商品の状態</th>
                <td>
    @php
        $conditions = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い'
        ];
    @endphp
</td>
            </tr>
        </table>

        <h2 class="section-title">コメント({{ $product->comments->count() }})</h2>
        <div class="comment-list">
            @foreach($product->comments as $comment)
                <div class="comment-item">
                    <div class="comment-user">
    <img src="{{ $comment->user->image ? asset('storage/' . $comment->user->image) : asset('images/default-user.png') }}" class="user-icon">
    <span>{{ $comment->user->name }}</span>
</div>
                    <div class="comment-text">{{ $comment->comment }}</div>
                </div>
            @endforeach
        </div>

        <div class="comment-form-box">
            <p style="font-weight: bold; margin-top: 30px;">商品へのコメント</p>
            <form action="{{ route('comment.store', $product->id) }}" method="POST" class="comment-form">
                @csrf
                <textarea name="comment" rows="5" required></textarea>
                <button type="submit" class="btn-submit">コメントを送信する</button>
                @if ($errors->has('comment'))
    <p style="color: red; font-size: 14px;">{{ $errors->first('comment') }}</p>
@endif
            </form>
        </div>
    </div>
</div>
@endsection