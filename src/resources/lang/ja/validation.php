<?php

return [
    'required' => ':attributeを入力してください',
    'email'    => ':attributeはメール形式で入力してください',
    'unique'   => '指定した:attributeは既に使用されています',
    'min'      => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'confirmed' => 'パスワードと一致しません',

    'attributes' => [
        'name'     => 'お名前',
        'email'    => 'メールアドレス',
        'password' => 'パスワード',
    ],
];