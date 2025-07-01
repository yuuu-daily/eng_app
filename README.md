## ブログCMSアプリ

### 概要（Overview）
パーソナルトレーニングの顧客向けクローズドアプリケーション。
トレーナーからユーザーへ向けて、**フィードバック**、**知識・情報・ノウハウ**を提供するためのプラットフォームです。(2025/04)

【一覧画面】
![一覧画面](/public/images/image.png)

### 使用技術（Tech Stack）
フロントエンド
- Vue: 3.3.13 / Vite 6.0.11 / Tailwind CSS 3.4.0
- Axios: 1.8.2

バックエンド
- Laravel 12 / MySQL 8.0 / Docker / Redis
- Jetstreamを使った認証

インフラ・その他
- Docker Compose

### 主な機能（Main Features）
- ユーザー認証（登録 / ログイン / ログアウト）
- 記事（登録・編集・削除）
- イベント（登録・編集・削除） 

### 開発手順
開発環境構築
1. docker環境準備
2. .envファイルを.env.exampleをコピーして環境に合わせて編集する(.envはコミットしない)
3. sail用にComposer依存関係のインストール ※1のコマンド
4.  ./vendor/bin/sail up -d
5. ./vendor/bin/sail npm ci
6. ./vendor/bin/sail artisan migrate:fresh --seed (データが全部消えるので注意 database/seeders/DatabaseSeeder.php参照)
7. ./vendor/bin/sail artisan storage:link


```
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/var/www/html \
-w /var/www/html \
laravelsail/php84-composer:latest \
composer install --ignore-platform-reqs
```

### 開発
1. ./vendor/bin/sail up -d
2. ./vendor/bin/sail npm run dev
3. Webブラウザでアクセスする(http://localhost)
4. ソースコードを編集すると、Webブラウザが自動更新される

### ビルド
1. ./vendor/bin/sail npm run build

### メンテナンス画面
1. ./vendor/bin/sail artisan down --render="errors::999" --secret="mente"
2. http://localhost/mente でアクセス可能。※ロードバランサーで絞っておくのが良い
3. ./vendor/bin/sail artisan up で戻る
