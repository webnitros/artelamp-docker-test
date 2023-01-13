ARG NGINX_VERSION=1.22

FROM nginx:1.22

COPY templates/default.conf.template /etc/nginx/templates/default.conf

CMD ["nginx", "-g", "daemon off;"]
