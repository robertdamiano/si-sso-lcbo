# Dockerfile for SI SSO LCBO
# Created by Robert Damiano
# Updated July 13 2016
FROM php:5.6-apache
MAINTAINER Robert Damiano <robert.f.damiano@gmail.com>
COPY src/ /var/www/html/
