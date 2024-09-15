#syntax=docker/dockerfile:1.4

# Base stage for dev and build using oven/bun:alpine
FROM oven/bun:alpine AS base

WORKDIR /srv/app

## Install dependencies using Bun
#COPY bun.lockb package.json ./
#RUN bun install

# Development image
FROM base AS dev

EXPOSE 3000
ENV PORT 3000
ENV HOSTNAME localhost

# Serve the Svelte app in development mode using Bun
CMD ["sh", "-c", "bun install; bun run dev --port 3000 --host 0.0.0.0"]

# Build stage for production
FROM base AS builder

COPY . .

# Build the Svelte app using Bun
RUN bun run build

# Production image, copying all files and running the app
FROM oven/bun:alpine AS prod

WORKDIR /srv/app

# Copy build artifacts
COPY --from=builder /srv/app/build ./build

# Add a non-root user for running the app securely
RUN addgroup --system --gid 1001 bun && \
    adduser --system --uid 1001 sveltekit

# Set the correct permissions for the application
RUN chown -R sveltekit:bun /srv/app/build

USER sveltekit

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Serve the Svelte app using Bun
CMD ["bun", "run", "start"]
