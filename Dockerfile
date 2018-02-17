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

#RUN cd /tmp \
#    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#    && php composer-setup.php \
#    && chmod +x composer.phar \
#    && mv composer.phar /usr/local/bin/composer \
#    && cd -

#RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.8/install.sh | bash \
#    && . $HOME/.bashrc \
#    && nvm install 9.5.0 \
#    && ln -s $HOME/.nvm/versions/node/v9.5.0/bin/node /usr/local/bin/node \
#    && ln -s $HOME/.nvm/versions/node/v9.5.0/bin/npm /usr/local/bin/npm

EXPOSE 8000

ADD ./ /code

WORKDIR /code

#RUN cd /code && composer install && npm install && npm run dev

CMD ["bash", "/code/entrypoint.sh"]
