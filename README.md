■自作アプリ「Room」の環境構築～起動手順


①下記リポジトリをローカルに持ってくる。(zipでダウンロードしてから解凍がおすすめ）
https://github.com/sakarice/docker-for-app-Room

②ローカルに落としたフォルダに入り、「docker-compose.yml」ファイルがある階層(=一番上の階層)でターミナルを開く。

③下記コマンドを実行してコンテナ起動。

docker-compose up -d --build


④コンテナの状態確認のため下記コマンド実行。
　アプリ,WEB,DBの3つのコンテナのStateがupになっていればOK。
[コマンド]
docker-compose ps

[実行結果]
          Name                         Command               State                 Ports
------------------------------------------------------------------------------------------------------
docker-for-app-room_app_1   docker-php-entrypoint php-fpm    Up      9000/tcp
docker-for-app-room_db_1    docker-entrypoint.sh mysqld      Up      0.0.0.0:3314->3306/tcp, 33060/tcp
docker-for-app-room_web_1   /docker-entrypoint.sh ngin ...   Up      0.0.0.0:10080->80/tcp


【補足】
　1行目　docker-for-app-room_app_1	…アプリコンテナ(phpとcomposerインスト済み。laravelは後で追加する)
　2行目　docker-for-app-room_db_1	…DBコンテナ(MySQLインスト済み)
　3行目　docker-for-app-room_web_1	…WEBサーバーコンテナ(nginxインスト済み)


④下記リポジトリをzipでダウンロード。
https://github.com/sakarice/app-Room-on-docker

⑤ダウンロードしたzipを①の手順で落としたdocker-for-app-Room\backend配下に解凍。

⑥backend配下のapp-Room-on-dockerフォルダをRoomにリネーム。
⇒下記ディレクトリ構成となる。
docker-for-app-Room
 |--backend
     |--Room
         |--app
         |--bootstrap
         |--・・・

⑦〇〇で渡した.envをdocker-for-app-Room\backend\Room直下に配置。


⑧Laravel含めたパッケージをインストールするため、ターミナルで下記コマンドを実行。(時間かかるのでしばらく放置)
docker-compose exec app bash
composer install
php artisan -V
⇒laravelの8系バージョンが表示されればOK

⑨下記コマンドでデータベースをミグレーション
php artisan migrate

⇒Migration table created successfully.　と表示されること。

⑩ブラウザで下記にアクセスし、Laravelのウェルカム画面が表示されること。
http://127.0.0.1:10080




