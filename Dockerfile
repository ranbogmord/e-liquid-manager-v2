FROM php:7.2
LABEL MAINTAINER = "root@ranbogmord.com"

ENV TINI_VERSION v0.17.0

RUN apt-get update && apt-get install -y curl unzip procps \
    && curl -o /tmp/consul.zip https://releases.hashicorp.com/consul/1.0.6/consul_1.0.6_linux_amd64.zip \
    && curl -o /tmp/consul-template.zip https://releases.hashicorp.com/consul-template/0.19.4/consul-template_0.19.4_linux_amd64.zip \
    && unzip /tmp/consul.zip \
    && unzip /tmp/consul-template.zip \
    && chmod +x consul consul-template \
    && mv consul /usr/local/bin \
    && mv consul-template /usr/local/bin

RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring tokenizer

ADD https://github.com/krallin/tini/releases/download/${TINI_VERSION}/tini /tini
RUN chmod +x /tini

RUN mkdir -p /consul/{config,data}

EXPOSE 8000

ADD ./entrypoint.sh /entrypoint.sh
ADD ./consul /consul/config
ADD ./ /code

RUN chmod +x /entrypoint.sh

WORKDIR /code

ENTRYPOINT ["/tini", "--", "/entrypoint.sh"]
CMD ["/usr/local/bin/php", "-S", "0.0.0.0:8000"]
