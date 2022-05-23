<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Demo of:
 *  - Table
 *  - Links.
 */
#[AsCommand(
    name: 'app:releases',
    description: 'Show details of Symfony releases',
)]
class ReleasesCommand extends Command
{
    private const SYMFONY_ENDPOINT = 'https://symfony.com';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly CacheInterface $cache
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('horizontal', null, InputOption::VALUE_NONE, 'Render horizontal table')
            ->addOption('vertical', null, InputOption::VALUE_NONE, 'Render vertical table')
            ->addOption('style', null, InputOption::VALUE_REQUIRED, 'Table style',
                'default', ['default', 'borderless', 'compact', 'symfony-style-guide', 'box', 'box-double', 'dump']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = $this->getReleases();
        if ('dump' === $input->getOption('style')) {
            dump($rows);

            return Command::SUCCESS;
        }

        $table = (new Table($output))
            ->setHeaderTitle('Symfony Releases')
            ->setHeaders(['Version', 'Release Date', 'Latest Patch', 'EOM', 'EOL'])
            ->addRows($rows)
        ;

        if ($input->getOption('horizontal')) {
            $table->setHorizontal();
        } elseif ($input->getOption('vertical')) {
            $table->setVertical();
        }
        $table->setStyle($input->getOption('style'));
        $table->render();

        return Command::SUCCESS;
    }

    /**
     * Download release metadata from Symfony website.
     */
    private function getReleases(): array
    {
        return $this->cache->get('symfony_releases', function () {
            $versions = $this->httpClient->request('GET', 'https://symfony.com/releases.json')->toArray()['maintained_versions'];
            $releases = [];
            foreach ($versions as $version) {
                $releases[$version] = $this->httpClient->request('GET', 'https://symfony.com/releases/'.$version.'.json');
            }
            foreach ($releases as $version => $response) {
                $release = $response->toArray();
                $releases[$version] = [
                    '<href=https://symfony.com/releases/'.$version.'>'.$version.'</>',
                    $release['release_date'], $release['latest_patch_version'], $release['eom'], $release['eol'],
                ];
            }

            return $releases;
        });
    }
}
