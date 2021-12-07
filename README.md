<img align="right" width="175px" height="100px" src="https://d33wubrfki0l68.cloudfront.net/5a27d37defa5f82b8542756e2ecb0108db2f5a45/eb216/assets/images/footer_logo.svg" />

# Docker-AmazonLinux2-PHP7.2-Phalcon3.4.5-GettingStarted

非公式のDocker+PHP+Phalcon環境です。

## もくじ
- [目的](#目的)
- [開発環境構築](#開発環境構築)
- [開発環境のバージョン](#開発環境のバージョン)
- [ソースコード自動生成](#ソースコード自動生成)
- [システム構成](#システム構成)
- [Phalconのルーティング](#Phalconのルーティング)
- [MySQLテストデータ](#MySQLテストデータ)
- [DBの永続化](#DBの永続化)
- [コード補完](#コード補完)

## 目的
- 簡単なPhalcon開発環境の構築
- 最低限のソースコード
  - nginxサーバーのロケーション設定
  - API実装
  - 画面表示
  - MySqlへのCRUD操作
- AWS環境のデプロイを意識したインフラ構築 (未テスト)

## 開発環境構築
1. [Docker][:docker:]をインストールします。
2. このリポジトリを複製またはダウンロードし実行します。
```bash
git clone https://github.com/hiromu-Links/Docker-AmazonLinux2-PHP7.2-Phalcon3.4.5-GettingStarted.git
cd Docker-AmazonLinux2-PHP7.2-Phalcon3.4.5-GettingStarted
docker-compose up -d
```
3. [Phalcon DevTools][:phalcon-devtools:]をインストールします。
```bash
docker container exec -it amazonlinux2-phpfpm bash
php composer.phar install
ln -s /usr/bin/vendor/phalcon/devtools/phalcon /usr/bin/phalcon
chmod ugo+x /usr/bin/phalcon
```

## 開発環境のバージョン
- OS
```bash
ver
Microsoft Windows [Version 10.0.19042.1348]
```

- Docker
```bash
docker -v
Docker version 20.10.10, build b485636
```
- AmazonLinux2
```bash
docker container exec -it amazonlinux2-phpfpm bash
cat /etc/os-release
NAME="Amazon Linux"
VERSION="2"
ID="amzn"
ID_LIKE="centos rhel fedora"
VERSION_ID="2"
PRETTY_NAME="Amazon Linux 2"
ANSI_COLOR="0;33"
CPE_NAME="cpe:2.3:o:amazon:amazon_linux:2"
HOME_URL="https://amazonlinux.com/"
```

- PHP
```bash
docker container exec -it amazonlinux2-phpfpm bash
php -v
PHP 7.2.34 (cli) (built: Oct 21 2020 18:03:20) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
    with Xdebug v3.1.2, Copyright (c) 2002-2021, by Derick Rethans
    with Zend OPcache v7.2.34, Copyright (c) 1999-2018, by Zend Technologies
```

- Phalcon
```bash
docker container exec -it amazonlinux2-phpfpm bash
php -r "echo Phalcon\Version::get();"
3.4.5
```

- Phalcon DevTools
```bash
docker container exec -it amazonlinux2-phpfpm bash
phalcon -v
Phalcon DevTools (3.4.11)

Environment:
  OS: Linux a784a2c27251 5.4.72-microsoft-standard-WSL2 #1 SMP Wed Oct 28 23:40:43 UTC 2020 x86_64
  PHP Version: 7.2.34
  PHP SAPI: cli
  PHP Bin: /usr/bin/php
  PHP Extension Dir: /usr/lib64/php/modules
  PHP Bin Dir: /usr/bin
  Loaded PHP config: /etc/php.ini
Versions:
  Phalcon DevTools Version: 3.4.11
  Phalcon Version: 3.4.5
  AdminLTE Version: 2.3.6
```

## ソースコード自動生成

Phalcon DevToolsをインストールすると下記のひな形を作成できます。
- コマンド一覧
```bash
docker container exec -it amazonlinux2-phpfpm bash
# コマンド一覧を出力
phalcon commands
Available commands:
  commands         (alias of: list, enumerate)
  controller       (alias of: create-controller)
  module           (alias of: create-module)
  model            (alias of: create-model)
  all-models       (alias of: create-all-models)
  project          (alias of: create-project)
  scaffold         (alias of: create-scaffold)
  migration        (alias of: create-migration)
  webtools         (alias of: create-webtools)
```

- プロジェクトのひな形作成 【とても便利】

下記、singleプロジェクトの作成例です。
```bash
docker container exec -it amazonlinux2-phpfpm bash
cd /var/www/html
phalcon create-project single
```

- コントローラークラスのひな形作成【便利】

下記、singleプロジェクトにSampleController.phpの作成例です。
```bash
docker container exec -it amazonlinux2-phpfpm bash
cd /var/www/html/single
phalcon controller post
```

- モデルクラスのひな形作成【便利】

RDSのテーブル名を指定するとテーブルカラム構成を考慮したモデルクラスのひな形作成します。モデルに独自実装等する場合等、--forceオプションを使用する際に意図しないひな形作成が行われるかもしれない点は注意が必要です。基本的には新規テーブル作成時のみの利用がお勧めです。<br>

- 事前準備<br>
    モデルクラスのひな形作成前にDB接続するための設定が必要です。<br>
  - 設定ファイル<br>
    single/app/config/config.php
  - 修正箇所
```bash
    'database' => [
    'adapter'     => 'Mysql',
    'host'        => 'db',
    'username'    => 'root',
    'password'    => 'password',
    'dbname'      => 'mydb',
    'charset'     => 'utf8',
    ],
```

下記、singleプロジェクトにsampleテーブルのモデルクラス、Sample.phpの作成例です。
```bash
docker container exec -it amazonlinux2-phpfpm bash
cd /var/www/html/single
phalcon model sample --get-set --force
```

下記、singleプロジェクトに全テーブルのモデルクラスを作成する例です。
```bash
docker container exec -it amazonlinux2-phpfpm bash
cd /var/www/html/single
phalcon all-models --get-set --force
```

- モデル+コントローラーを作成【とても便利】

下記、singleプロジェクトにsampleテーブルのモデル、コントローラークラスを作成します。<br>
```bash
docker container exec -it amazonlinux2-phpfpm bash
cd /var/www/html/single
phalcon scaffold sample --force
```
以下のファイルが生成されます。
- single/app/models/Sample.php
- single/app/controllers/SampleController.php
- single/app/view/sample/edit.phtml
- single/app/view/sample/index.phtml
- single/app/view/sample/new.phtml
- single/app/view/sample/search.phtml

##システム構成

Docker環境のシステム構成を簡単に纏めます。<br>
1. [nginx][:nginx:]サーバー(port:8081)へリクエストします。
2. data\nginx\conf\nginx.confのlocation設定により、<br>
   htdocs/single(プロジェクト名)/public/index.phpへルーティングされます。
3. htdocs/single(プロジェクト名)/public/index.phpへリクエストされたURLによりレスポンスを行います。

##Phalconのルーティング

プロジェクトの起動方法を簡単に纏めます。<br>
URLと処理されるクラス名、メソッド名は以下の関係です。<br>

下記、IndexControllerクラスのindexActionメソッドが処理されます。
```bash
http://localhost:8081
```

下記、SampleControllerクラスのsearchActionメソッドが処理されます。
```bash
http://localhost:8081/sample/search
```

表示される画面(view)の表示ロジックは以下の通りです。
1. [http://localhost:8081][:demo1:]をリクエスト
   1. htdocs/single(プロジェクト名)/public/index.phpへ"/"がリクエストされます。
   2. htdocs/single(プロジェクト名)/app/controllers/IndexController.phpが処理されます。
   3. htdocs/single(プロジェクト名)/app/views/index.phtmlが処理されます。(htmlのheadタグ情報)
   4. htdocs/single(プロジェクト名)/app/views/index/index.phtmlが処理されます。(htmlのbodyタグ情報)
2. [http://localhost:8081/sample/search][:demo2:]をリクエスト
   1. htdocs/single(プロジェクト名)/public/index.phpへ"/sample/search"がリクエストされます。
   2. htdocs/single(プロジェクト名)/app/controllers/SampleController.phpが処理されます。
   3. htdocs/single(プロジェクト名)/app/views/index.phtmlが処理されます。(htmlのheadタグ情報)
   4. htdocs/single(プロジェクト名)/app/views/sample/search.phtmlが処理されます。(htmlのbodyタグ情報)

##MySQLテストデータ

以下のファイルにテストデータを格納しました。<br>
data\mysql\init\sample.sql

##DBの永続化

名前付きボリュームを利用したデータの永続化を採用しています。<br>
参考サイト<br>
[Dockerのデータを永続化！Data Volume（データボリューム）の理解から始める環境構築入門][:reference1:]

```bash
#Dockerの起動
docker container up -d
#volumeの確認
docker volume ls
#DB更新処理を実行
#Dockerの停止
docker container down
#Dockerの起動
docker container up -d
#DBが消えない (永続化しないとdocker container downした再にDBが消えます)
```

##コード補完

[PhpStorm 2021.3][:PhpStorm:]でコード補完が出来ます。<br>
Phalcon3.4.5環境ですがコード補完ができるのはPhalcon3.1までの様なので注意です。<br>

1. PhpStormでプロジェクトを開く
2. 「外部ライブラリ」を左クリックし、「PHP インクルードパスの構成」を押下
　　<img src="data\github\img1.png" />
3. 「インクルードパス」の「プラスボタン」を押下し、以下のフォルダをインクルードする<br>
   ideフォルダは[こちら][:reference2:]です。<br>
   Phalcon3.1以降のコード補完が可能か未確認です。
```bash
   data/PhpStorm/ide
```
   <img src="data\github\img2.png" />

[:phalcon:]:          https://github.com/phalcon/cphalcon
[:docker:]:           https://www.docker.com
[:phalcon-devtools:]: https://github.com/phalcon/phalcon-devtools
[:nginx:]:            https://www.nginx.co.jp/
[:PhpStorm:]:         https://www.jetbrains.com/ja-jp/phpstorm
[:demo1:]:            http://localhost:8081
[:demo2:]:            http://localhost:8081/sample/search
[:reference1:]:       https://nishinatoshiharu.com/docker-volume-tutorial
[:reference2:]:       https://github.com/phalcon/phalcon-devtools/tree/3.1.x/ide