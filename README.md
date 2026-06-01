## アプリケーション名

勤怠管理アプリ

## 環境構築

1,Docker コンテナを起動

```
docker-compose up -d --build
```

2,php コンテナ内で Laravel の初期設定を行う
.env の作成 アプリケーションキーを生成

```
docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
```

3,データベースの接続設定を行う
.env の修正

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

4,マイグレーションを実行

```
php artisan migrate
```

5,シーディングを実行

```
php artisan db:seed
```

6,シンボリックリンクを作成

```
php artisan storage:link
```

## テスト環境構築

Laravelプロジェクト直下でテスト用の.envファイルを作成

```bash
cp .env .env.testing
```

.env.testing を以下のように修正

```env
APP_ENV=test
APP_KEY=

DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

APP_KEY は key:generate コマンドで自動生成されます
テスト用アプリケーションキーを生成

```bash
php artisan key:generate --env=testing
```

テストの実行

```bash
php artisan test
```

## テストユーザー

```
管理者
メールアドレス:admin@test.com
パスワード:password
一般ユーザー
メールアドレス:user@test.com
パスワード:password
```

## 使用技術

PHP：8.1.34
Laravel：8.83.8
mysql：8.0.26
nginx：1.21.1

## ER 図

![ER図](ER.drawio.png)

## URL

```text
一般ユーザー会員登録画面
http://localhost/register

一般ユーザーログイン画面
http://localhost/login

一般ユーザー勤怠登録画面
http://localhost/attendance

管理者ログイン画面
http://localhost/admin/login

管理者勤怠一覧画面
http://localhost/admin/attendance/list
```
