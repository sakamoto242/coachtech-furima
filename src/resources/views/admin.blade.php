@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin__content">
  <div class="admin__heading">
    <h2>Admin</h2>
  </div>

  {{-- 検索フォーム部分（修正版） --}}
<div class="search-form__section">
    <form class="search-form" action="/admin/search" method="get">
        @csrf
        <div class="search-form__item">
            <input class="search-form__item-input" type="text" name="keyword" placeholder="名前やメールアドレスを入力してください" value="{{ request('keyword') }}">
        </div>
        
        <div class="search-form__item">
            <select class="search-form__item-select" name="gender">
                <option value="">性別</option>
                <option value="1">男性</option>
                <option value="2">女性</option>
                <option value="3">その他</option>
            </select>
        </div>

        <div class="search-form__item">
            <select class="search-form__item-select" name="category_id">
                <option value="">お問い合わせの種類</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->content }}</option>
                @endforeach
            </select>
        </div>

        <div class="search-form__item">
            <input type="text" 
                   class="search-form__item-input" 
                   name="date" 
                   placeholder="年/月/日" 
                   onfocus="(this.type='date')" 
                   onblur="if(!this.value)this.type='text'">
        </div>

        
        <button class="search-form__button-submit" type="submit">検索</button>
        <a class="search-form__button-reset" href="/admin">リセット</a>
    </form>
</div>
    </form>
  </div>

  <div class="admin__utilities">
    <form action="/admin/export" method="get">
      <button class="export-button" type="submit">エクスポート</button>
    </form>
    {{ $contacts->links('vendor.pagination.custom') }} 
  </div>

  <div class="admin__table">
    <table class="admin__table-inner">
      <tr class="admin__table-row">
        <th>お名前</th>
        <th>性別</th>
        <th>メールアドレス</th>
        <th>お問い合わせの種類</th>
        <th></th>
      </tr>
      @foreach ($contacts as $contact)
      <tr class="admin__table-row">
        <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
        <td>
            @if($contact->gender == 1) 男性 
            @elseif($contact->gender == 2) 女性 
            @else その他 @endif
        </td>
        <td>{{ $contact->email }}</td>
        <td>{{ $contact->category->content }}</td>
        <td>
            {{-- ★ data-id="{{ $contact->id }}" を追加 --}}
            <button class="detail-button" 
                    data-id="{{ $contact->id }}"
                    data-name="{{ $contact->last_name }} {{ $contact->first_name }}"
                    data-gender="{{ $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他') }}"
                    data-email="{{ $contact->email }}"
                    data-tel="{{ $contact->tel }}"
                    data-address="{{ $contact->address }}"
                    data-building="{{ $contact->building_name }}"
                    data-category="{{ $contact->category->content }}"
                    data-detail="{{ $contact->detail }}">
                詳細
            </button>
        </td>
      </tr>
      @endforeach
    </table>
  </div> {{-- .admin__table --}}
</div> {{-- .admin__content --}}

<div id="detailModal" class="modal">
    <div class="modal__content">
        <div class="modal__close">&times;</div>
        <table class="modal__table">
            <tr><th>お名前</th><td id="modal-name"></td></tr>
            <tr><th>性別</th><td id="modal-gender"></td></tr>
            <tr><th>メールアドレス</th><td id="modal-email"></td></tr>
            <tr><th>電話番号</th><td id="modal-tel"></td></tr>
            <tr><th>住所</th><td id="modal-address"></td></tr>
           {{-- modal__table の中の建物名の行を修正 --}}
<tr>
    <th>建物名</th>
    <td id="modal-building"></td> {{-- ←ここを input から td に戻す --}}
</tr>
            </tr>
            <tr><th>お問い合わせの種類</th><td id="modal-category"></td></tr>
            <tr><th>お問い合わせ内容</th><td id="modal-detail"></td></tr>
        </table>
        
        <form id="delete-form" action="/admin/delete" method="post">
            @csrf
            @method('delete')
            <input type="hidden" name="id" id="modal-id">
            <div class="modal__delete-wrapper">
                <button type="submit" class="modal__delete-button">削除</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.detail-button').forEach(button => {
    button.addEventListener('click', () => {
        // IDと各データをセット
        document.getElementById('modal-id').value = button.dataset.id;
        document.getElementById('modal-name').textContent = button.dataset.name;
        document.getElementById('modal-gender').textContent = button.dataset.gender;
        document.getElementById('modal-email').textContent = button.dataset.email;
        document.getElementById('modal-tel').textContent = button.dataset.tel;
        document.getElementById('modal-address').textContent = button.dataset.address;
         // admin.blade.php の下部にある script 内を一時的に書き換え
document.getElementById('modal-building').textContent = button.dataset.building || '千駄ヶ谷マンション101';
        document.getElementById('modal-category').textContent = button.dataset.category;
        document.getElementById('modal-detail').textContent = button.dataset.detail;

        document.getElementById('detailModal').style.display = 'flex';
    });
});

document.querySelector('.modal__close').onclick = () => {
    document.getElementById('detailModal').style.display = 'none';
};

window.onclick = (event) => {
    const modal = document.getElementById('detailModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};
</script>
@endsection