Markdown
# COACHTECH フリマ（フリマアプリ）

ユーザー間で商品を売買できる、Stripe決済機能を搭載したフリマアプリケーションです。

## 1. 環境構築

### Dockerビルド
1. **リポジトリのクローンと移動**
   ```bash
   git clone [https://github.com/sakamoto242/coachtech-furima.git](https://github.com/sakamoto242/coachtech-furima.git)
   cd coachtech-furima
Laravel環境構築
コンテナ内へログイン

Bash
docker-compose exec php bash
初期設定（コンテナ内）

Bash
composer install
php artisan key:generate
php artisan migrate --seed
2. 使用技術
PHP 8.x

Laravel 10.x
MySQL 8.0
Stripe API (決済処理)

3. Stripe 決済の準備（ローカル開発環境）
Stripe CLIを使用してWebhookの転送を開始します。

Bash
.\stripe listen --forward-to localhost/stripe/webhook
※表示された whsec_... を .env の STRIPE_WEBHOOK_SECRET に設定してください。

## ER図
![ER図](https://github.com/sakamoto242/coachtech-furima/blob/main/er.png?raw=true)


 URL
開発環境: http://localhost/
phpMyAdmin: http://localhost:8080/
