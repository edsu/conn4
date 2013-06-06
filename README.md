conn4 is a simplistic connect 4 implementation along the lines of 
this [specification](https://gist.github.com/jgonera/69930780b2c4b18f2838).

Install
-------

* get a 64-bit ubuntu vm on ec2
* yes, 64-bit is a requirement :-)
* apt-get install apache2 php5 phpunit make php5-sqlite git
* git checkout https://github.com/edsu/conn4.git
* sudo mv conn4 /var/www/conn4
* sudo a2enmod rewrite
* edit /etc/apache2/sites-enabled/000-default so it has

  	DocumentRoot /var/www/conn4
  	<Directory />
  		Options FollowSymLinks
  		AllowOverride All
  	</Directory>

* sudo restart apache2
* play connect 4
