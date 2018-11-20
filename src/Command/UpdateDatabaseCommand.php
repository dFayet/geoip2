<?php
declare(strict_types=1);

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2018, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Bundle\GeoIP2Bundle\Command;

use GpsLab\Component\Compressor\CompressorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Stopwatch\Stopwatch;

class UpdateDatabaseCommand extends Command
{
    /**
     * @var Stopwatch|null
     */
    private $stopwatch;

    /**
     * @var CompressorInterface
     */
    private $compressor;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $cache = '';

    /**
     * @param Filesystem          $fs
     * @param CompressorInterface $compressor
     * @param string              $url
     * @param string              $cache
     */
    public function __construct(Filesystem $fs, CompressorInterface $compressor, string $url, string $cache)
    {
        $this->fs = $fs;
        $this->url = $url;
        $this->cache = $cache;
        $this->compressor = $compressor;

        parent::__construct();
    }

    /**
     * @param Stopwatch $stopwatch
     */
    public function setStopwatch(Stopwatch $stopwatch): void
    {
        $this->stopwatch = $stopwatch;
    }

    protected function configure()
    {
        $this
            ->setName('geoip2:update')
            ->setDescription('Downloads and update the GeoIP2 database')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'URL to downloaded GeoIP2 database',
                $this->url
            )
            ->addArgument(
                'target',
                InputArgument::OPTIONAL,
                'Target download path',
                $this->cache
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        $target = $input->getArgument('target');

        $io->title('Update the GeoIP2 database');

        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->start('update');
        }

        $tmp_zip = sys_get_temp_dir().DIRECTORY_SEPARATOR.basename(parse_url($url, PHP_URL_PATH));
        $tmp_unzip = sys_get_temp_dir().DIRECTORY_SEPARATOR.basename($target);

        $io->comment(sprintf('Beginning download of file: %s', $url));

        file_put_contents($tmp_zip, fopen($url, 'rb'));

        $io->comment('Download complete');
        $io->comment('De-compressing file');

        $this->fs->mkdir(dirname($target), 0777);
        $this->compressor->uncompress($tmp_zip, $tmp_unzip);

        $io->comment('Decompression complete');

        $this->fs->copy($tmp_unzip, $target, true);
        $this->fs->chmod($target, 0777);
        $this->fs->remove([$tmp_zip, $tmp_unzip]);

        $io->success('Finished downloading');

        if ($this->stopwatch instanceof Stopwatch) {
            $event = $this->stopwatch->stop('update');
            $io->writeln([
                sprintf('Time: <info>%.2F</info> s.', $event->getDuration() / 1000),
                sprintf('Memory: <info>%.2F</info> MiB.', $event->getMemory() / 1024 / 1024),
            ]);
        }

        return 0;
    }
}
