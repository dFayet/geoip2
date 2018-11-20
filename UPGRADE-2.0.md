UPGRADE FROM 1.x to 2.0
=======================

Updating Dependencies
---------------------

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

* The `gpslab.geoip2.proxy_reader` service has been created.

### Created service aliases

* The `GpsLab\Bundle\GeoIP2Bundle\Database\Reader` service alias for `gpslab.geoip2.proxy_reader` has been created.
* The `geoip2.reader` service alias for `gpslab.geoip2.proxy_reader` has been created.

### Renamed services

 * The `gpslab.command.geoip2.update` renamed to `gpslab.geoip2.update_database_command`.
 * The `gpslab.geoip2.component.gzip` renamed to `gpslab.geoip2.compressor`.
