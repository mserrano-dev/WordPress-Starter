# WordPress Starter Theme

##### /wp-json needs to have Permalink Settings > Common Settings set to 'Post name' (not 'Plain')

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

##### define a .env file (see .env.default)
> $ yarn install

| Command       | Result                                                                      |
| :------------ | :---------------------------------------------------------------------------|
| $ yarn git    | will add your creds to .git/config, as defined by .env variables            |
| $ yarn dev    | will watch for changes in the src folder, recompile & do a live reload      |
| $ yarn build  | will minimize the bundled files                                             |
| $ yarn deploy | duplicates theme directory with dynamically created WordPress meta info*    |

###### *generated from package.json name, version and author