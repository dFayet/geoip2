<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2018, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Bundle\GeoIP2Bundle\Database;

use GeoIp2\Database\Reader as GeoIp2Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\AnonymousIp;
use GeoIp2\Model\Asn;
use GeoIp2\Model\City;
use GeoIp2\Model\ConnectionType;
use GeoIp2\Model\Country;
use GeoIp2\Model\Domain;
use GeoIp2\Model\Enterprise;
use GeoIp2\Model\Isp;
use MaxMind\Db\Reader\InvalidDatabaseException;
use MaxMind\Db\Reader\Metadata;

class ProxyReader implements Reader
{
    /**
     * The path to the GeoIP2 database file.
     *
     * @var string
     */
    private $filename = '';

    /**
     * List of locale codes to use in name property from most preferred to least preferred.
     *
     * @var array
     */
    private $locales = '';

    /**
     * @var GeoIp2Reader|null
     */
    private $instance;

    /**
     * Constructor.
     *
     * @param string $filename The path to the GeoIP2 database file
     * @param array  $locales  List of locale codes to use in name property from most preferred to least preferred
     */
    public function __construct($filename, $locales = ['en'])
    {
        $this->filename = $filename;
        $this->locales = $locales;
    }

    /**
     * Read DB only if really necessary.
     *
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return GeoIp2Reader
     */
    private function lazyload(): GeoIp2Reader
    {
        if (!$this->instance) {
            $this->instance = new GeoIp2Reader($this->filename, $this->locales);
        }

        return $this->instance;
    }

    /**
     * This method returns a GeoIP2 City model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return City
     */
    public function city(string $ip_address): City
    {
        return $this->lazyload()->city($ip_address);
    }

    /**
     * This method returns a GeoIP2 Country model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return Country
     */
    public function country(string $ip_address): Country
    {
        return $this->lazyload()->country($ip_address);
    }

    /**
     * This method returns a GeoIP2 Anonymous IP model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return AnonymousIp
     */
    public function anonymousIp(string $ip_address): AnonymousIp
    {
        return $this->lazyload()->anonymousIp($ip_address);
    }

    /**
     * This method returns a GeoLite2 ASN model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return Asn
     */
    public function asn(string $ip_address): Asn
    {
        return $this->lazyload()->asn($ip_address);
    }

    /**
     * This method returns a GeoIP2 Connection Type model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return ConnectionType
     */
    public function connectionType(string $ip_address): ConnectionType
    {
        return $this->lazyload()->connectionType($ip_address);
    }

    /**
     * This method returns a GeoIP2 Domain model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return Domain
     */
    public function domain(string $ip_address): Domain
    {
        return $this->lazyload()->domain($ip_address);
    }

    /**
     * This method returns a GeoIP2 Enterprise model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return Enterprise
     */
    public function enterprise(string $ip_address): Enterprise
    {
        return $this->lazyload()->enterprise($ip_address);
    }

    /**
     * This method returns a GeoIP2 ISP model.
     *
     * @param string $ip_address an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException If the address is not in the database
     * @throws InvalidDatabaseException If the database is corrupt or invalid
     *
     * @return Isp
     */
    public function isp(string $ip_address): Isp
    {
        return $this->lazyload()->isp($ip_address);
    }

    /**
     * @throws \InvalidArgumentException If arguments are passed to the method
     * @throws \BadMethodCallException   If the database has been closed
     *
     * @return Metadata object for the database
     */
    public function metadata(): Metadata
    {
        return $this->lazyload()->metadata();
    }

    /**
     * Closes the GeoIP2 database and returns the resources to the system.
     */
    public function close(): void
    {
        $this->lazyload()->close();
    }
}
