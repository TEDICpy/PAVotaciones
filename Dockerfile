FROM php:5-apache

ENV REPO https://github.com/TEDICpy/PAVotaciones.git
ENV REPO2 https://github.com/TEDICpy/gs-share.git
ENV CARPETA /var/www/html/

#Copio el codigo
ADD ./public/* $CARPETA

EXPOSE 80
