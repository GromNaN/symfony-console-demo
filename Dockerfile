FROM php:8.1-zts-alpine3.15

RUN apk add\
    curl\
    git\
    bash bash-completion git-bash-completion\
    fish\
    && git config --global user.email "you@example.com" && git config --global user.name "Your Name"

RUN curl -o symfony-cli.apk -L https://github.com/symfony-cli/symfony-cli/releases/download/v5.4.8/symfony-cli_5.4.8_x86_64.apk \
    && apk add --allow-untrusted symfony-cli.apk\
    && symfony server:ca:install

RUN symfony new --dir /app --version next

WORKDIR /app

RUN mkdir /etc/bash_completion.d && bin/console completion bash > /etc/bash_completion.d/console

EXPOSE 8000
