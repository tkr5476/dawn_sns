# Dawn SNS

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## プロジェクト概要

Laravel を使用した SNS アプリケーションの開発

## 制作物

SNS アプリケーション

## 目的

1. カリキュラム[dawn]内で Laravel を使用し、具体的な開発手順を学ぶ
2. コードを読解し、自主的に調査しながら理解を深める

## 使用技術

-   バックエンド：Laravel9.0
-   フロントエンド：HTML, CSS, JavaScript, Bootstrap （今回のメインではない）

## ディレクトリ構成

```
dawn_sns/                      # プロジェクトのルートディレクトリ
│
├── app/                      # アプリケーションのコアコード
│   ├── Console/              # コンソールコマンド
│   ├── Exceptions/           # 例外ハンドラ
│   ├── Http/                 # HTTPリクエストとレスポンス
│   │   ├── Controllers/      # コントローラ
│   │   ├── Middleware/       # ミドルウェア
│   │   └── Requests/         # フォームリクエスト
│   ├── Models/               # Eloquentモデル
│   └── Providers/            # サービスプロバイダ
│
├── bootstrap/                # フレームワークの起動とオートローディング
├── config/                   # アプリケーションの設定ファイル
├── database/                 # データベース関連のファイル
│   ├── factories/            # モデルファクトリ
│   ├── migrations/           # データベースマイグレーション
│   └── seeders/              # データベースシーダー
│
├── public/                   # ウェブサーバのドキュメントルート
│   ��── css/                  # 公開用CSSファイル
│   ├── js/                   # 公開用JavaScriptファイル
│   ├── images/               # 公開用画像ファイル
│   └── storage/              # storage/app/publicへのシンボリックリンク
│       ├── images/           # 公開用の保存された画像
│       └── userIcon/         # ユーザープロフィール画像
│
├── resources/                # ビュー、未コンパイルのアセットファイル
│   ├── css/                  # 開発用CSSファイル
│   ├── js/                   # 開発用JavaScriptファイル
│   └── views/                # Bladeテンプレート
│       ├── layouts/          # レイアウトテンプレート
│       ├── top/              # トップページのview
│       └── user/             # その他の機能のview
│
├── routes/                   # アプリケーションのルート定義
├── storage/                  # ログ、コンパイル済みテンプレート、ファイルキャッシュ
│   └── app/                  # アプリケーション生成ファイル
│       └── public/           # 公開可能なファイル
│           ├── images/       # アップロードされた画像
│           └── userIcon/     # アップロードされたプロフィール画像
│
├── tests/                    # 自動テスト
│
├── vendor/                   # Composerの依存パッケージ
├── .env                      # 環境設定ファイル
├── .gitignore                # Gitで追跡しないファイル
├── composer.json             # Composerの設定ファイル
├── docker-compose.yml        # Dockerの設定ファイル
├── package.json              # Node.jsの設定ファイル
└── README.md                 # プロジェクトの概要と説明
```

## メモ

### MySQL へのアクセス

```
mysql -h mysql -u sail -p
パスワード: password
```

## 次回のタスク

```
添削後の修正

```



# 変数の情報源メモ

## views

### views/top/
#### top.blade.php
- $posts: PostsControllerから取得した投稿データ

#### update.blade.php
- $post: PostsControllerから取得した編集対象の投稿データ

### views/user/
#### loginUserProfile.blade.php
- $loginUser: UsersControllerから取得したログインユーザーの情報
- $loginUserPosts: UsersControllerから取得したログインユーザーの投稿データ

#### searchUser.blade.php
- $users: UsersControllerから取得した検索結果のユーザーデータ
- $followings: UsersControllerから取得したフォロー中のユーザーデータ

#### userProfile.blade.php
- $userProfile: UsersControllerから取得した表示対象ユーザーの情報
- $userPosts: UsersControllerから取得した表示対象ユーザーの投稿データ

#### editUserProfile.blade.php
- $user: UsersControllerから取得したログインユーザーの情報

#### followList.blade.php
- $followUsers: UsersControllerから取得したフォロー中のユーザーデータ
- $followPosts: UsersControllerから取得したフォロー中のユーザーの投稿データ

#### followerList.blade.php
- $followerUsers: UsersControllerから取得したフォロワーのユーザーデータ
- $followerPosts: UsersControllerから取得したフォロワーの投稿データ

## Controllers

### PostsController
- $posts: データベースから取得した全ての投稿データ
- $post: 特定の投稿データ（編集、削除時に使用）

### UsersController
- $loginUser: 現在ログインしているユーザーの情報
- $loginUserPosts: ログインユーザーの投稿データ
- $users: 検索結果のユーザーデータ
- $followings: ログインユーザーがフォローしているユーザーデータ
- $userProfile: 表示対象のユーザー情報
- $userPosts: 表示対象のユーザーの投稿データ
- $followUsers: フォロー中のユーザーデータ
- $followPosts: フォロー中のユーザーの投稿データ
- $followerUsers: フォロワーのユーザーデータ
- $followerPosts: フォロワーの投稿データ
