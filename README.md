# WordPress Starter Theme

##### example Apache2 conf for a basic local WordPress install
```
<VirtualHost *:80>
    ServerName dev-wordpress.local
    
    DocumentRoot /var/www/dev-wordpress.local
    
    <Directory /var/www/dev-wordpress.local/>
        AllowOverride All
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dev-wordpress.local.log.error
    CustomLog ${APACHE_LOG_DIR}/dev-wordpress.local.log.access combined
</VirtualHost>
```