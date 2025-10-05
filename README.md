# 模擬案件 フリマアプリ

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:hktooooo/free_market_app.git`
2.  DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
#SQLデータベース
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

#Mail Hog
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME="${APP_NAME}"

#Stripe
STRIPE_KEY=(各自のSTRIPE_KEYを記入)
STRIPE_SECRET=(各自のSTRIPE_SECRETを記入)
```

サンプルイメージのコピー
mkdir src/storage/app/public/product_images
cp src/public/images/sample_images/* src/storage/app/public/product_images


5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

ストレージフォルダのリンク作成
php artisan storage:link


mailHogの.env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME="Test"


## 使用技術(実行環境)
- PHP8.1.0
- Laravel8.83.27
- MySQL8.0.26

## ER図
![alt](erd.png)

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/