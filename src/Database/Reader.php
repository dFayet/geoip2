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

interface Reader
{
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
    public function city(string $ip_address): City;

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
    public function country(string $ip_address): Country;

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
    public function anonymousIp(string $ip_address): AnonymousIp;

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
    public function asn(string $ip_address): Asn;

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
    public function connectionType(string $ip_address): ConnectionType;

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
    public function domain(string $ip_address): Domain;

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
    public function enterprise(string $ip_address): Enterprise;

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
    public function isp(string $ip_address): Isp;

    /**
     * @throws \InvalidArgumentException If arguments are passed to the method
     * @throws \BadMethodCallException   If the database has been closed
     *
     * @return Metadata object for the database
     */
    public function metadata(): Metadata;

    /**
     * Closes the GeoIP2 database and returns the resources to the system.
     */
    public function close(): void;
}
