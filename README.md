conn4 is a simplistic [Connect Four](http://en.wikipedia.org/wiki/Connect_Four) implementation along the lines of this [specification](https://gist.github.com/jgonera/69930780b2c4b18f2838).

Install
-------

* get a 64-bit ubuntu vm on ec2
* yes, 64-bit is a requirement for the bitboard  :-)
* apt-get install apache2 php5 phpunit make php5-sqlite git
* git clone https://github.com/edsu/conn4.git
* sudo mv conn4 /var/www/conn4
* sudo a2enmod rewrite
* cp apache.conf /etc/apache2/sites-enabled/000-default
* sudo restart apache2
* play connect 4
