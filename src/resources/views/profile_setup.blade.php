@extends('layouts.app')

@section('content')
<style>
    .profile-setup-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        font-family: 'Inter', sans-serif;
    }
    .profile-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
    }
    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    
    .image-upload-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }
    .circle-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    .btn-upload {
        border: 1px solid #ff5a5f;
        color: #ff5a5f;
        padding: 8px 20px;
        border-radius: 4px;
        background: white;
        cursor: pointer;
        font-weight: bold;
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .btn-submit-red {
        width: 100%;
        background-color: #ff5a5f;
        color: white;
        padding: 14px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }
</style>

<div class="profile-setup-container">
    <h1 class="profile-title">プロフィール設定</h1>
@if ($errors->any())
        <div style="color: #ff5a5f; background: #fff5f5; border: 1px solid #ff5a5f; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- プロフィール画像 --}}
<div class="image-upload-section">
    <div class="circle-image" id="image-preview">
        {{-- 画像があれば表示、なければ文字を表示 --}}
        @if($user->image)
    <img src="{{ asset('storage/' . $user->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
@else
    <span style="color: #999;">No Image</span>
@endif
    </div>
    <input type="file" name="image" id="image" style="display:none;" onchange="previewImage(this)">
    <label for="image" class="btn-upload">画像を選択する</label>
</div>

{{-- プレビュー表示用のJavaScript（おまけ） --}}
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var preview = document.getElementById('image-preview');
            preview.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
        {{-- ユーザー名 (修正なし：これでOK) --}}
<div class="form-group">
    <label>ユーザー名</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
</div>

{{-- 郵便番号 (修正箇所) --}}
{{-- 修正後の郵便番号欄 --}}
<div class="form-group">
    <label>郵便番号</label>
    <input type="text" 
           name="post_code" 
           class="form-control" 
           value="{{ old('post_code', $user->post_code) }}" 
           maxlength="7" 
           oninput="value = value.replace(/[^0-9]+/g, '');">
           @error('post_code')
        <div style="color: #ff5a5f; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- 住所 (修正箇所) --}}
<div class="form-group">
    <label>住所</label>
    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
</div>

{{-- 建物名 (修正箇所) --}}
<div class="form-group">
    <label>建物名</label>
    <input type="text" name="building" class="form-control" value="{{ old('building', $user->building) }}">
</div>

        <button type="submit" class="btn-submit-red">更新する</button>
    </form>
</div>
@endsection