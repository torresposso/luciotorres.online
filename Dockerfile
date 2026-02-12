FROM oven/bun:1-alpine AS builder

WORKDIR /app

COPY package*.json ./
RUN bun install

COPY . .

RUN echo "=== Environment during build ===" && \
    echo "IMGPROXY_URL=${IMGPROXY_URL:-NOT SET}" && \
    echo "IMGPROXY_KEY=${IMGPROXY_KEY:-NOT SET}" && \
    echo "IMGPROXY_SALT=${IMGPROXY_SALT:-NOT SET}" && \
    echo "==============================="

RUN bun run build

FROM oven/bun:1-alpine AS runner

WORKDIR /app

ENV NODE_ENV=production

COPY --from=builder /app/dist /app/dist
COPY --from=builder /app/node_modules /app/node_modules

EXPOSE 8080

CMD ["x", "serve", "dist", "-p", "8080"]
