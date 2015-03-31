## Dependencias
Extensões do servidor
```shell
$ apt-get install python-software-properties
$ add-apt-repository ppa:ondrej/php5
$ apt-get update
$ apt-get install php5 nginx php5-fpm php5-apcu php5-sqlite php5-mysql php5-memcached memcached
$ apt-get install mysql-server mdbtools sqlite
```
## Composer
Aplicação Dependências
```shell
$ cd /var/www/app && php -r "readfile('https://getcomposer.org/installer');" | php
$ php composer.phar install --no-dev
$ php composer.phar dump-autoload --optimize
```
## Otimização

```shell
$ echo  $'
    opcache.enable=1 \n
    opcache.memory_consumption=128 \n
    opcache.interned_strings_buffer=8 \n
    opcache.max_accelerated_files=4000 \n
    opcache.revalidate_freq=60 \n
    opcache.fast_shutdown=1 \n
    opcache.enable_cli=1 \n
' >> /etc/php5/fpm/conf.d/05-opcache.ini
```
## Nginx 
Abra o arquivo ``nano /etc/nginx/site-available/default``, cole a configuração abaixo.
```shell
 server {
      listen   80;
      root /var/www/app;
      index index.php index.html;
      server_name sis.com;

        location / {
            try_files $uri /index.php$is_args$args;
            expires -1;
        }

        location ~* \.(html|json)$ {
                expires -1;
        }

        location ~* \.(sql|csv|json)$ {
            deny all;
            return 404;
        }

        location ~ \.php/ {           
            rewrite ^(.*.php)/ $1 last;
          }
        location ~ \.php$ {
            try_files $uri =404;
            fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
          }

          error_page 404 /404.html;
          error_page 500 502 503 504 /50x.html;
          location = /50x.html {
            root /usr/share/nginx/www;
          }
 }

```
## Outras configurações
Permição de execução no php para ``mb-export``
```shell
$ echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/mdb-export" >>  /etc/sudoers
```