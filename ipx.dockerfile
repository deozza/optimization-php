FROM node:22

WORKDIR /assets

CMD npx ipx serve --dir /assets
