@extends('layouts.app')

@section('content')
<style>
    /* 画面全体のレイアウト */
    .sell-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 0 20px;
        font-family: 'Inter', sans-serif;
    }
    .sell-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 40px;
    }
    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin-top: 40px;
        margin-bottom: 15px;
        border-bottom: 1px solid #333;
        padding-bottom: 10px;
    }
    .section-title.no-border {
        border-bottom: none;
        margin-bottom: 5px;
    }
    .image-upload-label { display: block; font-weight: bold; margin-bottom: 10px; }
    .image-upload-box {
        border: 1px solid #ccc;
        padding: 40px;
        text-align: center;
        border-radius: 4px;
        margin-bottom: 20px;
        background: #fafafa;
    }
    .btn-select-image {
        color: #ff5a5f;
        border: 1px solid #ff5a5f;
        padding: 8px 18px;
        border-radius: 4px;
        background: #fff;
        font-weight: bold;
        cursor: pointer;
        display: inline-block;
    }
    .category-label { font-weight: bold; display: block; margin-bottom: 15px; }
    .category-group { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px; }
    .cat-check:checked + .category-tag {
    background-color: #ff5a5f;
    color: white;
}
    .category-tag {
        border: 1px solid #ff5a5f;
        color: #ff5a5f;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        cursor: pointer;
    }
    .category-tag.active {
        background-color: #ff5a5f;
        color: white;
    }
    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }
    .form-group textarea { height: 120px; resize: none; }
    .price-input-wrapper { position: relative; }
    .price-input-wrapper span {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: bold;
    }
    .price-input-wrapper input { padding-left: 30px; }

    /* --- カスタムセレクトの修正 --- */
    .custom-select {
        position: relative;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #fff;
        cursor: pointer;
        width: 100%;
    }

    .custom-select-trigger {
        padding: 12px 40px 12px 12px;
        display: block;
        position: relative;
        z-index: 1;
    }

    .custom-select-trigger::after {
        content: '';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid #333;
        pointer-events: none;
    }

    .custom-options {
        position: absolute;
        top: 100%;
        left: -1px;
        right: -1px;
        border: 1px solid #444;
        background: #666666; /* 濃いグレー */
        display: none;
        z-index: 10;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .custom-select.open .custom-options {
        display: block;
    }

    .custom-option {
        display: block;
        padding: 12px 12px 12px 35px; 
        cursor: pointer;
        border-bottom: 1px solid #777;
        color: #ffffff; 
        font-size: 14px;
        position: relative;
        transition: background-color 0.2s;
    }

    .custom-option:last-child {
        border-bottom: none;
    }

    /* チェックマークのスタイル */
    .custom-option.selected::before {
        content: '✔';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #ffffff;
        font-weight: bold;
    }

    .custom-option:hover {
        background-color: #007bff; 
        color: #ffffff;
    }

    .btn-submit {
        width: 100%;
        background-color: #ff5a5f;
        color: white;
        padding: 15px;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }
</style>

<div class="sell-container">
    <h1 class="sell-title">商品の出品</h1>

    @if ($errors->any())
        <div style="color: red; background: #ffebeb; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="image-upload-label">商品画像</label>
        <div class="image-upload-box">
            <img id="preview" src="" style="max-width: 100%; max-height: 200px; display: none; margin: 0 auto 20px;">
            <label class="btn-select-image">
                画像を選択する
                <input type="file" name="image" id="image-input" style="display:none;" accept="image/*">
            </label>
        </div>

        <div class="section-title">商品の詳細</div>

        <div class="form-group">
    <label class="category-label">カテゴリー（複数選択可）</label>
    <div class="category-group">
        @foreach($categories as $category)
            <div class="category-item">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat-{{ $category->id }}" style="display: none;" class="cat-check">
                {{-- デザイン用のラベル：クリックでチェックボックスが反応する --}}
                <label for="cat-{{ $category->id }}" class="category-tag">
                    {{ $category->content }}
                </label>
            </div>
        @endforeach
    </div>
</div>
        @endforeach
    </div>
            <input type="hidden" name="category" id="selected-category" value="">
        </div>

        <div class="form-group">
            <label>商品の状態</label>
            <div class="custom-select">
                <div class="custom-select-trigger">選択してください</div>
                <div class="custom-options">
                    <span class="custom-option" data-value="1">良好</span>
                    <span class="custom-option" data-value="2">目立った傷や汚れなし</span>
                    <span class="custom-option" data-value="3">やや傷や汚れあり</span>
                    <span class="custom-option" data-value="4">状態が悪い</span>
                </div>
            </div>
            <input type="hidden" name="condition" id="condition-hidden">
        </div>

        <div class="section-title">商品名と説明</div>
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label>ブランド名</label>
            <input type="text" name="brand" value="{{ old('brand') }}">
        </div>

        <div class="form-group">
            <label>商品の説明</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <div class="section-title no-border">販売価格</div>
        <div class="form-group">
            <div class="price-input-wrapper">
                <span>¥</span>
                <input type="number" name="price" value="{{ old('price') }}">
            </div>
        </div>

        <button type="submit" class="btn-submit">出品する</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // --- カテゴリー選択の連動処理 ---
    const categoryTags = document.querySelectorAll('.category-tag');

    categoryTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const checkboxId = this.getAttribute('for');
            const checkbox = document.getElementById(checkboxId);

            setTimeout(() => {
                if (checkbox.checked) {
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
                }
            }, 10);
        });
    });

    // --- 商品の状態（カスタムセレクト） ---
    const select = document.querySelector('.custom-select');
    const trigger = document.querySelector('.custom-select-trigger');
    const options = document.querySelectorAll('.custom-option');
    const hiddenInput = document.getElementById('condition-hidden');

    select.addEventListener('click', function() {
        this.classList.toggle('open');
    });

    options.forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            const value = this.getAttribute('data-value');
            const text = this.innerText;
            
            options.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            trigger.innerText = text;
            hiddenInput.value = value;
            select.classList.remove('open');
        });
    });

    // --- 画像プレビュー ---
    const imageInput = document.getElementById('image-input');
    const preview = document.getElementById('preview');
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection