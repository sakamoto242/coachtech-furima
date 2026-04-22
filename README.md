Markdown
# COACHTECH フリマ（フリマアプリ）

ユーザー間で商品を売買できる、Stripe決済機能を搭載したフリマアプリケーションです。
## 1. 環境構築

### Dockerビルド
1. **リポジトリのクローン**
   ```bash
   git clone https://github.com/sakamoto242/mogitate.git
Dockerコンテナの起動

Bash
docker-compose up -d --build
Laravel環境構築
コンテナ内へログイン

Bash
docker-compose exec php bash
プロジェクトディレクトリ(src)内での操作

Bash
cd src
composer install
初期設定コマンド

Bash
php artisan key:generate
php artisan migrate
php artisan db:seed

２. 使用技術
PHP: 8.3.0
Laravel: 8.83.27
MySQL: 8.0.26

### Stripe 決済の準備（ローカル開発環境）
1. Stripe CLIをインストールし、ログインする。
2. 以下のコマンドで Webhook の転送を開始する。
   .\stripe listen --forward-to localhost/stripe/webhook
3. 表示された `whsec_...` を `.env` の `STRIPE_WEBHOOK_SECRET` に設定する。

３. ER図
<img width="693" height="641" alt="スクリーンショット 2026-02-11 220008" src="https://github.com/user-attachments/assets/8b9182e8-6dee-44a0-b47d-320c1469366f" />


４. URL
開発環境: http://localhost/

phpMyAdmin: http://localhost:8080/
