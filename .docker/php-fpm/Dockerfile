FROM php:8-fpm-alpine

# Setup entrypoint
# As this is for localhost only, we can run as root
USER root

# Setup user and base dir

ARG USER=docker
ARG GROUP=docker
ARG UID=1000
ARG GID=1000

RUN set -eux; \
	addgroup -g ${GID} -S ${GROUP}; \
	adduser -u ${UID} -D -S -G ${GROUP} ${USER};

RUN mkdir -p /srv; \
    chown -R ${USER}:${GROUP} /srv;

USER ${UID}:${GID}

# Add composer from the docker image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

#Configure composer
RUN set -eux; \
    composer --global config process-timeout 600; \
    composer clear-cache;

ENV PATH="${PATH}:/home/${USER}/.composer/vendor/bin"

# we use /srv as the workdir for all container applications
WORKDIR /srv