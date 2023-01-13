ARG NGINX_VERSION=1.22

FROM nginx:1.22

COPY default.conf.template /etc/nginx/templates/default.conf.template

CMD ["nginx", "-g", "daemon off;"]
