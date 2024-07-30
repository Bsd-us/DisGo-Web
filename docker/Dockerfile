FROM ghcr.io/bsd-us/symfony:20240730

RUN phpaddmod mongodb \
 && apt update \
 && apt install -y git \
 && apt clean

COPY ./00-prodify /startup/
RUN chmod +x /startup/00-prodify
