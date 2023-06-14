<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Song;
use App\Repository\SongRepository;
use App\Service\Quiz\AlbumQuiz;
use App\Service\Quiz\GapQuiz;
use App\Service\Quiz\SongQuiz;

/**
 * @author  Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2023 DocCheck Community GmbH
 */
class SongService
{

    public function __construct(
        private SongFactory $factory,
        private SongRepository $repository,
        private SentenceDetector $detector
    ) {
    }

    public function createSong(SongData $data): Song
    {
        $song = $this->factory->createSong();
        $this->factory->mapData($song, $data);

        $this->repository->save($song, true);

        return $song;
    }

    public function getSongs(bool $hardcoreMode = false){
        if ($hardcoreMode){
            return $this->repository->findAll();
        }

        return $this->repository->findAllAlbumSongs();
    }

    public function getSongQuiz(bool $hardcoreMode = false): SongQuiz
    {
        $songQuiz = new SongQuiz;

        $songs = $this->getSongs($hardcoreMode);

        $total = count($songs);
        // get 4 random one
        /** @var Song[] $randomSongs */
        $randomSongs = [];
        $answers = [];
        for ($i = 0; $i < 4; $i++) {
            $song = null;
            while (is_null($song)) {
                $song = $songs[(rand(0, $total-1))];
                if (!is_null($song->getName()) && in_array($song->getName(), $answers)) {
                    $song = null;
                }
            }

            $randomSongs[$i] = $song;
            $answers[$i] = $song->getName();
        }

        $selectedSongNr = array_rand($randomSongs);
        $selectedSong = $randomSongs[$selectedSongNr];

        if (count($selectedSong->getText()) < 2){
            return $this->getSongQuiz();
        }

        $randomTextLine = $selectedSong->getText()[rand(0, count($selectedSong->getText()) - 1)];

        $songQuiz->setAnswers($answers);
        $songQuiz->setCorrectAnswer($selectedSongNr);
        $songQuiz->setText($randomTextLine);
        return $songQuiz;
    }

    public function getAlbumQuiz(bool $hardcoreMode = false): AlbumQuiz
    {
        $albumQuiz = new AlbumQuiz();

        $songs = $this->getSongs($hardcoreMode);

        $total = count($songs);
        // get 4 random one
        /** @var Song[] $randomSongs */
        $randomSongs = [];
        $answers = [];
        for ($i = 0; $i < 4; $i++) {
            $song = null;
            while (is_null($song)) {
                $song = $songs[rand(0, $total-1)];
                if (in_array($song->getAlbum(), $answers)) {
                    $song = null;
                }
            }

            $randomSongs[$i] = $song;
            $answers[$i] = $song->getAlbum();
        }

        $selectedSongNr = array_rand($randomSongs);
        $selectedSong = $randomSongs[$selectedSongNr];

        $randomTextLine = $selectedSong->getText()[rand(0, count($selectedSong->getText()) - 1)];

        $albumQuiz->setAnswers($answers);
        $albumQuiz->setCorrectAnswer($selectedSongNr);
        $albumQuiz->setText($selectedSong->getName());
        return $albumQuiz;
    }

    public function getGapQuiz(bool $hardcoreMode = false): GapQuiz
    {
        $gapQuiz = new GapQuiz();

        $songs = $this->getSongs($hardcoreMode);

        $total = count($songs);
        // get 4 random one
        /** @var Song[] $randomSongs */

        // we need 1 to remove 1 before 1 after = 3 plus 3 fakes = 6 at least
        $song = null;
        while (is_null($song) || count($song->getText()) <= 7) {
            $song = $songs[rand(0, $total-1)];
        }

        $complete = implode(' ', $song->getText());
        $sentences = ($this->detector->detectSentences($complete));

        $randomIndex = rand(1, count($song->getText()) - 2);
        $randomSentence = $sentences[$randomIndex];
        unset($sentences[$randomIndex]);

        $before = $sentences[$randomIndex - 1];
        $after = $sentences[$randomIndex + 1];
        unset($sentences[$randomIndex - 1]);
        unset($sentences[$randomIndex + 1]);

        $answers = [];
        $answers[] = $randomSentence;

        $fallbackCounter = 0;
        while (count($answers) < 4) {
            $randomIndex = rand(0, count($sentences) - 1);
            if (array_key_exists($randomIndex, $sentences)) {
                if (!in_array($sentences[$randomIndex], $answers)) {
                    $answers[] = $sentences[$randomIndex];
                }
            }

            $fallbackCounter++;
            if ($fallbackCounter > 100) {
                $answers[] = 'Joker';
            }
        }

        // shuffle answers
        $correctAnswerText = $answers[0];
        shuffle($answers);
        foreach ($answers as $index =>  $answer){
            if ($answer === $correctAnswerText){
                $gapQuiz->setCorrectAnswer($index);
            }
        }

        $gapQuiz->setText($before . ' ...... ' . $after);
        $gapQuiz->setAnswers($answers);

        return $gapQuiz;
    }
}
