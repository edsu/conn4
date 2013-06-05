apt-get install apache2 php5 phpunit make php5-sqlite

sudo a2enmod rewrite

/etc/apache2/sites-enabled/000-default

	DocumentRoot /var/www
	<Directory />
		Options FollowSymLinks
		AllowOverride All
	</Directory>


