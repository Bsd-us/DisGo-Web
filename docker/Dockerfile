FROM shinsenter/symfony:latest

LABEL org.opencontainers.image.source https://github.com/${GITHUB_REPO}

RUN phpaddmod mongodb \
 && apt update \
 && apt install -y git \
 && apt clean

COPY ./00-prodify /startup/
RUN chmod +x /startup/00-prodify
