ARG NGINX_VERSION=${NGINX_VERSION}

FROM nginx:${NGINX_VERSION}

COPY templates /etc/nginx/templates

CMD ["nginx", "-g", "daemon off;"]
