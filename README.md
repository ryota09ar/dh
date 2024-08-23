# このアプリについて
学習用で使うcrud-tempです。
# 環境について
## 概要
## 使い方
既にプロジェクトを作った状態（＝`composer create-project`した状態）になっているので、下の手順を踏んで⑤まで終了すればlaravelのトップページが見れます。
### 初回構築時
#### ①docker, docker-composeをインストールする。
確認
```bash
docker --version
docker-compose --version
```
※Dockerが立ち上がっているか確認(Windows)(タスクバー下の「隠れているインジゲーターを表示」→Dockerのマークが"Docker is running..."になっていることを確認)
![image](https://user-images.githubusercontent.com/58587065/101975639-62256e00-3c81-11eb-95ef-2be3a1f7d469.png)


#### ②おまじない
```bash
echo "export DOCKER_CONTENT_TRUST=1" >> ~/.bashrc
```
(zsh使ってる人は./zshrc)

#### ③clone
```bash
git clone https://github.com/sdb-interns/empty-crud-temp-10.git empty-crud-temp
```

#### ④実行環境ごとの設定
##### M1 Macの人
M1系列のCPU(M1, M2, M2 Proなど)を搭載しているMacを使用している方は、`docker-compose.yml`ファイルを開いて以下の行のコメントアウトを外す
```yml
platform: linux/amd64
```

##### Windowsの人
Windowsの人は[こちらのサイト](https://bluebirdofoz.hatenablog.com/entry/2019/10/24/221517)を見てGNUをインストールする


#### ⑤コンテナを立ち上げる

以下のコマンドを打ってください。
```bash(Winはcmd)
make init
```
全てのコンテナが立ち上がって、開発用のデータベースのcreate、migrate、seedまで完了する。

### 作業終了時
```bash
make down
```

### 作業再開時
```bash
make up
```

### コマンドの例
全部Makefile見れば書いてある。もし「このコマンドいつも使うけど長くて打つの面倒くさい」とか「毎回このコマンド組み合わせて打ってるな」とかあったら追加すると便利になるよ〜。
```bash
# コンテナが起動中かどうか確認する。「state」が「up」になってたら起動中。docker勉強するとわかる
make ps

# php artisan migrate
make migrate

# php artisan migrate:fresh --seed
make fresh

# php artisan tinker
make tinker

# npm install
make npm

# npm run watch
make npm-watch

# appコンテナに入る
make app
```
