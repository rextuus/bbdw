<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\SongCollector;
use App\Service\SongData;
use App\Service\SongService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'bbdw:collect-songs')]
class CollectSongsCommand extends Command
{

    public function __construct(private SongCollector $collector, private SongService $songService) {
        parent::__construct('bbdw:collect-songs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $songUrls = $this->collector->getSongUrls();

        foreach ($songUrls as $nr => $songUrl){
            dump($nr);
            dump($songUrl);
            if (!is_null($songUrl) && $nr > 27 && $nr < 407){
                $songData = new SongData();
                $text = $this->collector->getTextFromUrl($songUrl, $songData);
                $songData->setUrl($songUrl);
                dump($songData);
                $this->songService->createSong($songData);
            }
        }
        return Command::SUCCESS;
    }
}
