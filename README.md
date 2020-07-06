# dockvalet

Use valet drivers inside laradock.

## Installation

Go to your Projects directory (a directory, containing laradock and projects) and execute the following command:
```bash
$ git clone --recursive https://github.com/Tarik02/dockvalet .dockvalet
```

The, replace your `default.conf` nginx configuration inside laradock with this (and edit it):
```
server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

     root /;

    # For https
    # listen 443 ssl;
    # listen [::]:443 ssl ipv6only=on;
    # ssl_certificate /etc/nginx/ssl/default.crt;
    # ssl_certificate_key /etc/nginx/ssl/default.key;

    # Uncomment the next line, replacing this path to dockvalet inside nginx container.

    location /41c270e4-5535-4daa-b23e-c269744c2f45/ {
        internal;
        alias /;
        try_files $uri $uri/;
    }

    location / {
        rewrite ^ "/path/to/dockvalet/server.php" last;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 "/path/to/dockvalet/server.php";

    location ~ \.php$ {
        try_files $uri /path/to/dockvalet/server.php =404;
        fastcgi_pass php-upstream;
        fastcgi_index /path/to/dockvalet/server.php;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        #fixes timeouts
        fastcgi_read_timeout 600;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/default_error.log;
    access_log /var/log/nginx/default_access.log;
}
```

## License

The project is released under the MIT license. Read the [license](https://github.com/Tarik02/dockvalet/blob/master/LICENSE.md) for more information.
