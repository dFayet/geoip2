UPGRADE FROM 1.x to 2.0
=======================

Updating Dependencies
---------------------

### Change configuration of services

Change dependency service `geoip2.reader` to `GpsLab\Bundle\GeoIP2Bundle\Database\Reader`.

Before:

```yml
services:
    App\Service\MyCustomService:
        arguments: [ '@geoip2.reader' ]
```

After:

```yml
services:
    App\Service\MyCustomService:
        arguments: [ '@GpsLab\Bundle\GeoIP2Bundle\Database\Reader' ]
```

### Change dependency in controller

Change dependency service `geoip2.reader` to `GpsLab\Bundle\GeoIP2Bundle\Database\Reader`.

Before:

```php
class MyController extends Controller
{
    public function index(Request $request): Response
    {
        // get a GeoIP2 City model
        $record = $this->get('geoip2.reader')->city($request->getClientIp());

        // ...
    }
}
```

After:

```php
use GpsLab\Bundle\GeoIP2Bundle\Database\Reader;

class MyController extends Controller
{
    public function index(Request $request): Response
    {
        // get a GeoIP2 City model
        $record = $this->get(Reader::class)->city($request->getClientIp());

        // ...
    }
}
```

### Change dependency

Change dependency class `GeoIp2\Database\Reader` to `GpsLab\Bundle\GeoIP2Bundle\Database\Reader` interface.

Before:

```php
use GeoIp2\Database\Reader;

class MyCustomService
{
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
```

After:

```php
use GpsLab\Bundle\GeoIP2Bundle\Database\Reader;

class MyCustomService
{
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
```

All changes
-----------

### Created classes and interfaces

* The `GpsLab\Bundle\GeoIP2Bundle\Database\Reader` interface has been created.
* The `GpsLab\Bundle\GeoIP2Bundle\Database\ProxyReader` class has been created.

### Created services

* The `GpsLab\Bundle\GeoIP2Bundle\Database\ProxyReader` service has been created.
* The `GpsLab\Bundle\GeoIP2Bundle\Database\Reader` service has been created.

### Renamed services

 * The `gpslab.command.geoip2.update` renamed to `GpsLab\Bundle\GeoIP2Bundle\Command\UpdateDatabaseCommand`.
 * The `gpslab.geoip2.component.gzip` renamed to `GpsLab\Component\Compressor\GzipCompressor`.

### Removed services

 * The `geoip2.reader` has been removed.
