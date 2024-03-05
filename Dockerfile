FROM php:8.3.4RC1-apache-bookworm

RUN docker-php-ext-install mysqli

#RUN docker-php-ext-enable mysqli
