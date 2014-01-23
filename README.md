# asset-linker

Providers helpers that make linking to frontend assets easy, based on your
current environment.

This package is not interested in any compilation steps, pre-processing,
minification, etc. Use grunt, gulp or Assetic for that.

## Plan

Aims to provide configurable links for assets that are:

-   Locally hosted (e.g. `/public/assets`)
-   Hosted on a different domain (e.g. `http://static.example.com/`)
-   Presets for CDN hosting - S3, Akamai, Cloudflare, etc.
