FROM amazonlinux:2.0.20180622.1

# システムのアップデート
RUN yum update -y

# システムの言語を日本語に対応させる
RUN yum install -y glibc-langpack-ja \
    unlink /etc/localtime \
    ln -s /usr/share/zoneinfo/Japan /etc/localtime \
    echo "LANG=ja_JP.UTF-8" | tee /etc/sysconfig/i18n

# タイムゾーンを日本に変更
RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    echo -e 'ZONE="Asia/Tokyo"\nUTC=true' | tee /etc/sysconfig/clock

# サイトのデータを置くフォルダを作成し、実行権限を変更
RUN mkdir -p /var/www/html/

# nginxを入れる
RUN amazon-linux-extras install -y nginx1.12

# nginxをフォアグラウンドで立ち上げる
ENTRYPOINT /usr/sbin/nginx -g 'daemon off;' -c /etc/nginx/nginx.conf