conn4 is a simplistic connect 4 implementation along the lines of 
this [specification](https://gist.github.com/jgonera/69930780b2c4b18f2838).

Install
-------

apt-get install apache2 php5 phpunit make php5-sqlite

sudo a2enmod rewrite

/etc/apache2/sites-enabled/000-default

	DocumentRoot /var/www
	<Directory />
		Options FollowSymLinks
		AllowOverride All
	</Directory>


