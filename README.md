
# Websocket by Kyrne

## About

Websockets is a replacement for flarum/pusher, it's a locally hosted version that functions identically. Pusher can get very expensive for large forums and can cause a serious headache if you must comply with GDPR. This websocket implementation is completely PHP based, no need to install extra software or tools.


## Extension Compatibility 

Currently, websocket supports:
* fof/reactions
* fof/gamification
* fof/polls
* The normal pusher behavior you've come to enjoy (new posts, discussions, and notifications)

## Installing

Please follow Extiverse's installation instructions here: https://extiverse.com/premium/subscriptions

# Setup

There are several options for setting up this extension, firstly, make sure it's installed on your forum, but don't enable it just yet, we have some prep work to do. If you run into any problems, or just want someone to walk you through the install steps, don't hesitate to reach out to me on Discord @**Kyrne#9728**, and I'll be happy to help! I have also included a troubleshooting section down below, it contains fixes to some common problems. Once again, if you have any issues you need help with, please reach out to me.

## Option 1 - Proxy w/ SSL (Recommended)

With this setup option, we will use Nginx to proxy the websocket requests to the websocket server.

### Step 1:

Locate your Nginx server configuration, usually located here: /etc/nginx/sites-enabled/{your_site}. 
> Note: If you are double proxying your connections through 2 Nginx instances, use the most downstream (first) one. 

### Step 2:

Open the server block from step 1 in your favorite editor (nano, vim, etc), right now it should look something similar to this:
```nginx
server {
        root /var/www/flarum/public;
        index index.php index.html index.htm index.nginx-debian.html;
        server_name your.domain;

        include /var/www/flarum/.nginx.conf;

        error_page 404 = @notfound;

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
        }

        location ~ /\.ht {
                deny all;
		}


    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/your.domain/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/your.domain/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot

}

server {
    if ($host = your.domain) {
        return 301 https://$host$request_uri;
    } # managed by Certbot

    server_name your.domain;

    listen 80;
    return 404; # managed by Certbot
}
```

### Step 3:

Add the following to the very top of the file: 

```nginx
map $http_upgrade $type {
  default "web";
  websocket "ws";
}
```
### Step 4:

Add the following inside of your SSL server block: 
```nginx
  # Your default configuration comes here...

	location / {
		try_files /nonexistent @$type;
	}

	location @ws {
	    proxy_pass             http://127.0.0.1:6001;
	    proxy_set_header Host  $host;
	    proxy_read_timeout     60;
	    proxy_connect_timeout  60;
	    proxy_redirect         off;

	    # Allow the use of websockets
	    proxy_http_version 1.1;
	    proxy_set_header Upgrade $http_upgrade;
	    proxy_set_header Connection 'upgrade';
	    proxy_set_header Host $host;
	    proxy_cache_bypass $http_upgrade;
	}
```
You can now save and close this file. These configurations will tell Nginx about your websocket server, and how to send requests to it.

### Step 5:

Open up the default provided `.nginx.conf` that came with your Flarum installation, it is usually just outside of your `public` directory. (Usually something like `/var/www/flarum` or `/var/www/html`) The top few lines should look like: 
```
# Pass requests that don't refer directly to files in the filesystem to index.php
location / {
  try_files $uri $uri/ /index.php?$query_string;
}

# The following directives are based on best practices from H5BP Nginx Server Configs
# https://github.com/h5bp/server-configs-nginx
```

### Step 6: 

Edit the top lines to look like this:
```
# Pass requests that don't refer directly to files in the filesystem to index.php
location @web {
  try_files $uri $uri/ /index.php?$query_string;
}
```
Replacing the `/` after `location` with `@web`.

**We are now done with the Nginx configuration! The hard part is over!**

### Step 7:

Once you have completed all the above steps, you can now enable the extension. Once enabled, the settings will pop up, make sure to turn on the switch that says "Reverse Proxy Support" leave everything else as is. Then save your settings.

### Step 8: 
You are now ready to turn on the websocket server! Skip down to that section.

## Option 2 - Websocket server handles SSL

For this method, you will need to know the location of your fullchain ssl certificate for your domain (or the domain that the websocket server will be hosted on) as well as the private key for the same domain. Take a moment to locate these files, and copy down the path somewhere. If you don't know where these files are, take a look at your Nginx or Apache configurations, they will often have the paths in these files. If you use letsencrypt they can be commonly found in the `/etc/letsencrypt/live/your.domain` folder.
> Note: These files must also be readable by your web server (usually `www-data`).

### Step 1 - Portfowarding:

As you will not be using your webserver to route the traffic, you will need to open a port so visitors of your site can connect to the websocket server. If you are running your server on a VPS like DigitalOcean you simply need to execute a command to open the port. For Ubuntu run `sudo ufw allow {port}`. If you are running your server at home or through a router you control, make sure that port is pointing to your web server. You can look up guides on the internet on how to forward ports for your specific router online.
> Note: The default port as installed is `2083`.

If you are using Cloudflare please note that there are only certain ports that are allowed through their network on the basic plan. These ports are 
-   2053
-   2083
-   2087
-   2096
-   8443

If you decide to change the port to something other than `2083` make sure to specify that in the extension settings in the Admin Panel.

### Step 2 - SSL Cert and Private Key:

Go ahead and enable the extension in your admin panel at this point. Once the settings pop up, fill in the paths to both the SSL Certificate (Local Cert) and Private Key (Local PK). If your certificate is self-sign, make sure to turn on the switch labeled "Self-Signed Certificate Support." If your keys are encrypted with a passphrase, enter it into its respective field in the settings. Then save your settings.

### Step 3 - All Done:

Your setup should be complete, you are now ready to turn on the websocket server! Skip down to that section.

## Option 3 - Unsecure websocket server

Please use this method with caution. This method should only be used (and is only possible with) a forum that doesn't use https. Ideally, your forum should be using https as you are handling sensitive user data such as passwords. If you must use this method, it is the simplest to set up. You need only to install it and follow step 1 from option #2. Then run the websocket server.

# Starting the Websocket Server

Starting the server is the easiest part, simply navigate to your Flarum root directory and run `php flarum websocket:serve`. The server will take up a terminal session which isn't ideal. If you'd like to daemonize the process, please look up how to use `nohup` or something similar.
> Note: I would recommend testing your setup first before daemonizing it.

# Verifying Successful Setup

Once you have followed all install steps and your websocket server is now running, everything should work at this point. I do however recommend you verify things are working. This is most easily achieved by turning on [debug mode](https://flarum.org/docs/troubleshoot.html#step-1-turn-on-debug-mode). Once debug mode is on open your browser developer tools and navigate to the console, then refresh your page. If everything is working, you should see and output similar to:
```output
["State changed","initialized -> connecting"]
["Connecting",{"transport":"ws","url":"wss://your.domain:port/app/ekYx3xmU5ECWzglOnF9u83iHS6ztClH1
?protocol=7&client=js&version=5.1.1&flash=false"}]
["State changed","connecting -> connected with new socket ID 696199201.622665801"]
["Event sent",{"event":"pusher:subscribe","data":{"auth":"","channel":"public"}}]
["Event recd",{"event":"pusher_internal:subscription_succeeded","channel":"public"}]
["No callbacks on public for pusher:subscription_succeeded"]
```
If this is the output you see, the client side is working propely, lets test the backend.

After starting the websocket server, you will be able to see all the connections that are established and events that are sent. What you will want to do is make a new post on the forum and watch the console output from the websocket server. Once that post is created, you should see something like this in the console output:

```console
Connection id 213252186.960942107 sending message {"channel":"public","event":"newPost","data":"{\"postId\":158,\"discussionId\":43,\"tagIds\":[1]}"}
```

If both your client-side, and server look similar to those, **Congratulations!** Everything is working well and you are all done!

If you are shown an error or some other output, please proceed to the troubleshooting section.

# Troubleshooting

Unfortunately, this extension is quite complex and there a quite a few things that can go wrong with the setup. Every server and setup is different and can create new and unique challenges. As I said before, if you run into any issues you cannot solve yourself, or would like someone to help you through it, don't hesitate to reach out to me on Discord @**Kyrne#9728**.

## "Failed to listen on tcp://0.0.0.0:{port}"

This usually means that the port you are trying to use for your websocket server is already in use by another process on your machine. Simply change the port you are going to use in the Extension Settings. Don't forget to port forward this port and update any configurations you made using this port in the above steps (such as the Nginx proxy forward port). 

## "ERR_SSL_VERSION_OR_CIPHER_MISMATCH"

This error is usually caused when the SSL Certificate and Private Key either do not match, or are for the wrong domain. It can also occur if you are not using the full chain certificate. Please ensure the private key and certificate paths are correct and are for the right domain and you are using the full chain certificate.

##  "WebSocket is closed before the connection is established."

The websocket server will not work double proxied. Please ensure that the Nginx instance that is forwarding the request to the websocket server is the most downstream (the closest to the user). Also, ensure that the websocket server is running and on the correct port.

## A 404 Error or "ERROR: The connection to {address} was interrupted while the page was loading

If you are using the proxy setup, make sure Nginx is proxying the connection to the right port as well as IP (127.0.0.1 corresponds to the same device).
If you are using the port forwarding method, make sure the port is open. While running the webserver use a tool like [Open Port Checker](https://www.yougetsignal.com/tools/open-ports/) to see if the port is open. If not, double-check your port forwarding settings, or try a different port.

## 426, 400, or any 500 error

These can be more complicated to fix and vary greatly across different setups in terms of their cause. Please contact me on Discord @**Kyrne#9728** and I'll help you fix your specific problem.

# In Closing

Thank you very much for supporting my extension development work through your subscription. If there are any features you would like to see in the future, please reach out to me and I will do my best to make your request a reality.

Take care fellow Flarumite!
