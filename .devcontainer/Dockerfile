FROM tomsik68/xampp

RUN apt-get update
RUN apt-get install default-mysql-client -y

RUN rm -rf /opt/lampp/htdocs
RUN ln -s /www /opt/lampp/htdocs

# Create link for mysql socket
RUN mkdir /var/run/mysqld/
RUN touch /opt/lampp/var/mysql/mysql.sock
RUN ln -s /opt/lampp/var/mysql/mysql.sock /var/run/mysqld/mysqld.sock