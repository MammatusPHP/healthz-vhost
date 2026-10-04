# Basic health check vhost

![Continuous Integration](https://github.com/MammatusPHP/healthz-vhost/workflows/Continuous%20Integration/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/mammatus/healthz-vhost/v/stable.png)](https://packagist.org/packages/mammatus/healthz-vhost)
[![Total Downloads](https://poser.pugx.org/mammatus/healthz-vhost/downloads.png)](https://packagist.org/packages/mammatus/healthz-vhost/stats)
[![Type Coverage](https://shepherd.dev/github/MammatusPHP/healthz-vhost/coverage.svg)](https://shepherd.dev/github/MammatusPHP/healthz-vhost)
[![License](https://poser.pugx.org/mammatus/healthz-vhost/license.png)](https://packagist.org/packages/mammatus/healthz-vhost)

Ready-made [`Vhost`](https://github.com/MammatusPHP/http-server-contracts/blob/master/src/Configuration/Vhost.php) configuration, HTTP handlers, and Kubernetes-style probes for a dedicated **healthz** virtual host. Install it in a [MammatusPHP](https://github.com/MammatusPHP/app) application that uses [mammatus/http-server](https://github.com/MammatusPHP/http-server); the server Composer plugin discovers this package (via `extra.mammatus.http.server.has-vhosts`) and wires routes, Helm probe paths, and static file serving from [`public/`](public/).

The vhost listens on port **9666** and registers the Mammatus group **`healthz`** ([`Type::Daemon`](https://github.com/MammatusPHP/groups/blob/main/src/Type.php)).

# Install

To install via [Composer](https://getcomposer.org/), use the command below. Composer picks the latest compatible version and applies a `^` constraint.

```
composer require mammatus/healthz-vhost
```

Your application also needs [mammatus/http-server](https://github.com/MammatusPHP/http-server) (or another stack that consumes the same attributes and `Vhost` contract). This package does not start an HTTP server by itself.

# Routes

All handlers belong to vhost **`healthz`** ([`Vhost`](https://github.com/MammatusPHP/http-server-attributes/blob/main/src/Vhost.php) attribute). Each exposes a static [`handle()`](src/HealthzHandler.php) method marked **`@api`** so static analysis treats it as an entrypoint for routing.

| HTTP method | Path | Handler | Purpose |
|-------------|------|---------|---------|
| `GET` | [`/`](src/IndexHandler.php) | [`IndexHandler`](src/IndexHandler.php) | Redirects to [`/index.html`](public/index.html) |
| `GET` | [`/healthz`](src/HealthzHandler.php) | [`HealthzHandler`](src/HealthzHandler.php) | JSON health payload |
| `GET` | [`/probe/liveness`](src/LivenessProbeHandler.php) | [`LivenessProbeHandler`](src/LivenessProbeHandler.php) | Liveness probe ([`ProbeType::Liveness`](https://github.com/MammatusPHP/http-server-attributes/blob/main/src/ProbeType.php)) |
| `GET` | [`/probe/readiness`](src/ReadinessProbeHandler.php) | [`ReadinessProbeHandler`](src/ReadinessProbeHandler.php) | Readiness probe |
| `GET` | [`/probe/startup`](src/StartUpProbeHandler.php) | [`StartUpProbeHandler`](src/StartUpProbeHandler.php) | Startup probe |

Successful health and probe handlers respond with `200`, `Content-Type: application/json`, and body `{"result":"healthy"}`.

# Vhost configuration

[`HealthCheckVhost`](src/HealthCheckVhost.php) implements [`Vhost`](https://github.com/MammatusPHP/http-server-contracts/blob/master/src/Configuration/Vhost.php):

- **`name()`** returns `healthz`.
- **`port()`** returns `9666`.
- **`webroot()`** returns [`WebrootPath`](https://github.com/MammatusPHP/http-server-webroot/blob/master/src/WebrootPath.php) pointing at this package's [`public/`](public/) directory (static demo page and assets).
- **`middleware()`** yields no extra middleware.

Example attribute usage on a handler (same style as this package):

```php
use Mammatus\Http\Server\Attributes\HttpMethod;
use Mammatus\Http\Server\Attributes\Probe;
use Mammatus\Http\Server\Attributes\ProbeType;
use Mammatus\Http\Server\Attributes\Route;
use Mammatus\Http\Server\Attributes\Vhost;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

/** @api */
#[Vhost('healthz')]
#[Route(HttpMethod::GET, '/probe/liveness')]
#[Probe(ProbeType::Liveness)]
final class LivenessProbeHandler
{
    public static function handle(): ResponseInterface
    {
        return new Response(
            Response::STATUS_OK,
            ['Content-Type' => 'application/json'],
            '{"result":"healthy"}',
        );
    }
}
```

More attribute reference: [mammatus/http-server-attributes](https://github.com/MammatusPHP/http-server-attributes/blob/main/README.md).

# License

The MIT License (MIT)

Copyright (c) 2026 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
