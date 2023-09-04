FROM nginx:1.23.0

# Remove default page
RUN rm -rf /usr/share/nginx/html \
    && rm -rf /etc/nginx/conf.d/default.conf

COPY ./config/nginx.conf /etc/nginx/nginx.conf
COPY ./config/vhost.conf /etc/nginx/conf.d/vhost.conf
