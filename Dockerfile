FROM php:7.2
LABEL MAINTAINER = "root@ranbogmord.com"


RUN apt-get update && apt-get install -y curl unzip \
    && curl -o /tmp/consul.zip https://releases.hashicorp.com/consul/1.0.6/consul_1.0.6_linux_amd64.zip \
    && curl -o /tmp/consul-template.zip https://releases.hashicorp.com/consul-template/0.19.4/consul-template_0.19.4_linux_amd64.zip \
    && unzip /tmp/consul.zip \
    && unzip /tmp/consul-template.zip \
    && chmod +x consul consul-template \
    && mv consul /usr/local/bin \
    && mv consul-template /usr/local/bin

RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring tokenizer

RUN mkdir -p /consul/{config,data}

EXPOSE 8000

ADD ./entrypoint.sh /entrypoint.sh
ADD ./consul /consul/config
ADD ./ /code

WORKDIR /code

CMD ["bash", "/entrypoint.sh"]
