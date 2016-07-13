# Dockerfile for SI SSO LCBO
# Created by Robert Damiano
# Updated July 13 2016
FROM httpd:2.4
MAINTAINER Robert Damiano <robert.f.damiano@gmail.com>
COPY ./public-html/ /usr/local/apache2/htdocs/
