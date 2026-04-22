@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin: 50px auto; color: #333; font-family: 'Inter', sans-serif; background-color: #fff; padding: 20px; border-radius: 8px;">
    <h2 style="text-align: center; margin-bottom: 40px; font-weight: bold; font-size: 24px;">商品の出品</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像セクション --}}
        <div class="form-group" style="margin-bottom: 40px;">
            <p style="font-weight: bold; margin-bottom: 15px;">商品画像</p>
            <div id="drop-zone" style="border: 2px dashed #ccc; padding: 40px; text-align: center; border-radius: 6px; background-color: #fafafa; transition: 0.3s;">
                <label for="image" style="display: inline-block; border: 1px solid #ff5a5f; color: #ff5a5f; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; background: #fff;">
                    画像を選択する
                </label>
                <input type="file" name="image" id="image" style="display: none;" accept="image/*">
                @error('image')
                    <p style="color: #ff5a5f; font-size: 14px; margin-top: 10px; font-weight: bold;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 商品の詳細セクション --}}
        <div style="border-bottom: 1px solid #eee; margin-bottom: 25px; padding-bottom: 8px;">
            <p style="font-weight: bold; color: #666; margin: 0; font-size: 16px;">商品の詳細</p>
        </div>

        {{-- カテゴリー --}}
        <div class="form-group" style="margin-bottom: 30px;">
            <label style="font-weight: bold; display: block; margin-bottom: 15px;">カテゴリー</label>
            <div class="category-group">
                @foreach($categories as $category)
                    <div class="category-item">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat-{{ $category->id }}" style="display: none;">
                        <label for="cat-{{ $category->id }}" class="category-tag">
                            {{ $category->content }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('categories')
                <p style="color: #ff5a5f; font-size: 14px; margin-top: 10px; font-weight: bold;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の状態 --}}
        <div class="form-group" style="margin-bottom: 40px;">
            <p style="font-weight: bold; margin-bottom: 10px;">商品の状態</p>
            <div style="position: relative;">
                <select name="condition" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; appearance: none; background-color: #fff; font-size: 16px;">
                    <option value="">選択してください</option>
                    <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                    <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                <span style="position: absolute; right: 15px; top: 20px; pointer-events: none; width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 6px solid #999;"></span>
            </div>
            @error('condition')
                <p style="color: #ff5a5f; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品名と説明セクション --}}
        <div style="border-bottom: 1px solid #eee; margin-bottom: 25px; padding-bottom: 8px;">
            <p style="font-weight: bold; color: #666; margin: 0; font-size: 16px;">商品名と説明</p>
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <p style="font-weight: bold; margin-bottom: 10px;">商品名</p>
            <input type="text" name="name" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" value="{{ old('name') }}">
            @error('name')
                <p style="color: #ff5a5f; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <p style="font-weight: bold; margin-bottom: 10px;">ブランド名</p>
            <input type="text" name="brand" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" value="{{ old('brand') }}">
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <p style="font-weight: bold; margin-bottom: 10px;">商品の説明</p>
            <textarea name="description" rows="5" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;">{{ old('description') }}</textarea>
            @error('description')
                <p style="color: #ff5a5f; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group" style="margin-bottom: 40px;">
    <p style="font-weight: bold; margin-bottom: 10px;">販売価格</p>
    <div style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; background-color: #fff;">
        {{-- 左側のグレーの背景部分 --}}
        <span style="padding: 12px 20px; background-color: #eee; border-right: 1px solid #ccc; font-weight: bold; font-size: 18px; color: #333;">
            ¥
        </span>
      
        <input type="number" name="price" style="flex: 1; padding: 12px; border: none; outline: none; font-size: 16px;" value="{{ old('price') }}">
    </div>
    @error('price')
        <p style="color: #ff5a5f; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</p>
    @enderror
</div>

        <button type="submit" style="width: 100%; background-color: #ff5a5f; color: white; padding: 16px; border: none; border-radius: 4px; font-weight: bold; font-size: 18px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 6px rgba(255, 90, 95, 0.2);">
            出品する
        </button>
    </form>
</div>

<style>
    .category-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .category-tag {
        display: inline-block;
        border: 1px solid #ff5a5f;
        color: #ff5a5f;
        padding: 5px 15px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 13px;
        background-color: #fff;
        transition: all 0.2s ease;
        user-select: none;
    }

    input[type="checkbox"]:checked + .category-tag {
        background-color: #ff5a5f;
        color: #fff;
    }

    .category-tag:hover {
        opacity: 0.8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 画像プレビュー
        const imageInput = document.getElementById('image');
        const dropZone = document.getElementById('drop-zone');
        
        const selectLabel = dropZone.querySelector('label[for="image"]');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                let oldImg = document.getElementById('preview-img');
                if (oldImg) oldImg.remove();

                const img = document.createElement('img');
                img.id = 'preview-img';
                img.src = event.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '250px';
                img.style.borderRadius = '4px';
                img.style.display = 'block';
                img.style.margin = '0 auto';
                img.style.cursor = 'pointer'; 

                
                img.addEventListener('click', function() {
                    imageInput.click();
                });

                
                selectLabel.style.display = 'none';
                dropZone.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection