# warframe-api-private-proxy-php

A minimal PHP script acting as the private proxy for the [warframe-api-front-proxy](https://github.com/dsinn/warframe-api-front-proxy) Cloudflare Worker.

## Why

The Warframe API's WAF returns 403 for all requests originating from Cloudflare Worker egress IPs. This private proxy runs on shared hosting and makes plain HTTP requests from a non-Cloudflare IP, acting as a passthrough from the front proxy to the Warframe API.

## Architecture

See the [system diagram in browse.wf](https://github.com/dsinn/browse.wf#warframe-api-proxy-optional).

## How It Works

The script accepts a `?url=` query parameter containing the Warframe API URL to fetch, performs a plain HTTP request, and returns the response as JSON.

Access is controlled by comparing the `X-Private-Proxy-Secret` request header against the `PRIVATE_PROXY_SECRET` server environment variable (both default to empty string). A mismatch returns 403.

## Setup

1. Upload `proxy.php` to a subdirectory of your `public_html` with an obscure name
2. Copy `.htaccess.example` to `.htaccess` in the same directory
3. Set the file permissions: `proxy.php` → 644, directory → 755

## Environment Variables

The `.htaccess` file is used to set server environment variables:

| Variable | Description |
|---|---|
| `PRIVATE_PROXY_SECRET` | *(Optional)* Shared secret that the front proxy sends via `X-Private-Proxy-Secret`; requests with a mismatched value are rejected with 403. Defaults to empty string, which passes when the front proxy is also unconfigured. |

## Companion Repos

- [warframe-api-front-proxy](https://github.com/dsinn/warframe-api-front-proxy) — the Cloudflare Worker that calls this private proxy
- [browse.wf](https://github.com/dsinn/browse.wf) — the app that initiates the request chain
