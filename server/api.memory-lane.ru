server {
        listen *:80;
        server_name api.memory-lane.ru;
	index index.php index.html index.htm;
        location / {
		proxy_set_header X-Real-IP  $remote_addr;
		proxy_set_header X-Forwarded-For $remote_addr;
		proxy_set_header Host $host;
		proxy_pass http://127.0.0.1:8080;
		#add_header 'Access-Control-Allow-Origin' '*';
		# add_header 'Access-Control-Allow-Origin' 'http://localhost:3000';
		#add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
		#add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization';
		#try_files $uri $uri/ /index.php?$args;
				
		}
	error_page 401 404 /custom_40x.php;
	location = /custom_40x.html {
			root /var/www/api_legacy;
			internal;
			add_header 'Access-Control-Allow-Origin' '*';
	}
	location ~ /\.ht {
		deny all;
	}

}
