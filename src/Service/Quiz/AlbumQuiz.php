<?php

declare(strict_types=1);

namespace App\Service\Quiz;

/**
 * @author  Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2023 DocCheck Community GmbH
 */
class AlbumQuiz
{
    private string $text;
    private array $answers;
    private int $correctAnswer;

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): AlbumQuiz
    {
        $this->text = $text;
        return $this;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function setAnswers(array $answers): AlbumQuiz
    {
        $this->answers = $answers;
        return $this;
    }

    public function getCorrectAnswer(): int
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(int $correctAnswer): AlbumQuiz
    {
        $this->correctAnswer = $correctAnswer;
        return $this;
    }
}
