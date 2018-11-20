<?php
declare(strict_types=1);

/**
 * Lupin package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 */

namespace GpsLab\Bundle\GeoIP2Bundle\Tests\Command;

use GpsLab\Bundle\GeoIP2Bundle\Command\UpdateDatabaseCommand;
use GpsLab\Component\Compressor\GzipCompressor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Stopwatch\Stopwatch;

class UpdateDatabaseCommandTest extends TestCase
{
    private const URL = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';

    private $target = '';

    protected function setUp()
    {
        $this->target = tempnam(sys_get_temp_dir(), 'GeoLite2-City.mmdb');
    }

    protected function tearDown()
    {
        if (file_exists($this->target) && is_writable($this->target)) {
            unlink($this->target);
        }
    }

    /**
     * @throws \Exception
     */
    public function testRun(): void
    {
        $output = new BufferedOutput();
        $command = new UpdateDatabaseCommand(new Filesystem(), new GzipCompressor(), self::URL, $this->target);
        $command->run(new ArrayInput([]), $output);

        if (class_exists(Stopwatch::class)) {
            $command->setStopwatch(new Stopwatch());
        }

        $this->assertContains('[OK] Finished downloading', $output->fetch());
        $this->assertFileExists($this->target);
    }
}
