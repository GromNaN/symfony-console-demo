FROM php:8.1-zts-alpine3.15

RUN apk add\
    icu-dev\
    curl\
    git\
    bash bash-completion git-bash-completion\
    fish\
    asciinema\
    && git config --global user.email "you@example.com" && git config --global user.name "Your Name"
RUN docker-php-ext-configure intl && docker-php-ext-install intl && docker-php-ext-install pcntl

RUN curl -o symfony-cli.apk -L https://github.com/symfony-cli/symfony-cli/releases/download/v5.4.8/symfony-cli_5.4.8_x86_64.apk \
    && apk add --allow-untrusted symfony-cli.apk\
    && symfony server:ca:install

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ADD . /app

WORKDIR /app

RUN symfony composer install

RUN mkdir /etc/bash_completion.d && bin/console completion bash > /etc/bash_completion.d/console
RUN bin/console completion fish > /etc/fish/completions/console.fish

EXPOSE 8000
