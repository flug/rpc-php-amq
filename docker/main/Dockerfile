FROM php:7.2-cli-alpine



RUN docker-php-ext-install bcmath mbstring\
           &&  apk  add --no-cache \
           git


WORKDIR /workspace


COPY --from=composer /usr/bin/composer /usr/bin/composer





#ENTRYPOINT ['bin/console']
