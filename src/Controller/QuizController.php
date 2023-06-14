<?php

namespace App\Controller;

use App\Service\SongService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/quiz/start', name: 'app_quiz_start')]
    public function start(Request $request, SongService $songService): Response
    {
        $text = 'So kommst du nicht ins Camp!';
        return $this->render('quizz/start.html.twig', [
            'text' => $text,
        ]);
    }

    #[Route('/quiz/looser', name: 'app_quiz_loose')]
    public function loose(Request $request): Response
    {
        $text = 'So kommst du nicht ins Camp!';
        return $this->render('quizz/loose.html.twig', [
            'text' => $text,
            'correctAnswer' => $text,
            'correctAnswerText' => $request->get('correct'),
        ]);
    }

    #[Route('/quiz/winner', name: 'app_quiz_win')]
    public function win(Request $request): Response
    {
        $text = 'Dich wollen wir Sonntag im Mosh pit vor der Bühne sehen. Angeben ist ein Ding.';
        return $this->render('quizz/win.html.twig', [
            'text' => $text,
        ]);
    }

    #[Route('/quiz/song', name: 'app_quiz_song_search')]
    public function index(Request $request, SongService $songService): Response
    {
        $gapQuiz = $songService->getSongQuiz();

        $form = $this->createForm(SongSearchFormType::class, ['answers' => $gapQuiz]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($correctAnswerText, $won) = $this->evaluate($form);

            if ($won){
                return $this->redirectToRoute('app_quiz_album_search');
            }

            // looser
            return $this->redirectToRoute(
                'app_quiz_loose',
                ['correct' => $correctAnswerText]
            );
        }

        return $this->render('quizz/song_search.html.twig', [
            'form' => $form->createView(),
            'text' => $gapQuiz->getText(),
            'gapQuiz' => $gapQuiz->getCorrectAnswer(),
            'correctAnswerText' => $gapQuiz->getAnswers()[$gapQuiz->getCorrectAnswer()],
        ]);
    }

    #[Route('/quiz/album', name: 'app_quiz_album_search')]
    public function albumSearch(Request $request, SongService $songService): Response
    {
        $album = $songService->getAlbumQuiz();

        $form = $this->createForm(SongSearchFormType::class, ['answers' => $album]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($correctAnswerText, $won) = $this->evaluate($form);

            if ($won){
                return $this->redirectToRoute('app_quiz_gap_search');
            }

            // looser
            return $this->redirectToRoute(
                'app_quiz_loose',
                ['correct' => $correctAnswerText]
            );
        }


        return $this->render('quizz/album_search.html.twig', [
            'form' => $form->createView(),
            'text' => $album->getText(),
            'gapQuiz' => $album->getCorrectAnswer(),
            'correctAnswerText' => $album->getAnswers()[$album->getCorrectAnswer()],
        ]);
    }

    #[Route('/quiz/gap', name: 'app_quiz_gap_search')]
    public function songGap(Request $request, SongService $songService): Response
    {
        $gapQuiz = $songService->getGapQuiz();

        $form = $this->createForm(SongSearchFormType::class, ['answers' => $gapQuiz]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($correctAnswerText, $won) = $this->evaluate($form);

            if ($won){
                return $this->redirectToRoute('app_quiz_win');
            }

            // looser
            return $this->redirectToRoute(
                'app_quiz_loose',
                ['correct' => $correctAnswerText]
            );
        }


        return $this->render('quizz/song_search.html.twig', [
            'form' => $form->createView(),
            'text' => $gapQuiz->getText(),
            'gapQuiz' => $gapQuiz->getCorrectAnswer(),
            'correctAnswerText' => $gapQuiz->getAnswers()[$gapQuiz->getCorrectAnswer()],
        ]);
    }

    #[Route('/quiz/cover', name: 'app_quiz_cover')]
    public function cover(Request $request, SongService $songService): Response
    {
        $gapQuiz = $songService->getGapQuiz();

        $form = $this->createForm(SongSearchFormType::class, ['answers' => $gapQuiz]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($correctAnswerText, $won) = $this->evaluate($form);

            if ($won){
                return $this->redirectToRoute('app_quiz_win');
            }

            // looser
            return $this->redirectToRoute(
                'app_quiz_loose',
                ['correct' => $correctAnswerText]
            );
        }


        return $this->render('quizz/cover.html.twig', [
            'form' => $form->createView(),
            'text' => $gapQuiz->getText(),
            'gapQuiz' => $gapQuiz->getCorrectAnswer(),
            'correctAnswerText' => $gapQuiz->getAnswers()[$gapQuiz->getCorrectAnswer()],
        ]);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public function evaluate(FormInterface $form): array
    {
        $data = $form->getData();
        $correctAnswer = (int)$data['correctAnswer'];
        $correctAnswerText = $data['correctAnswerText'];

        $won = false;
        if ($form->get('answer1')->isClicked() && $correctAnswer === 0) {
            $won = true;
        }
        if ($form->get('answer2')->isClicked() && $correctAnswer === 1) {
            $won = true;
        }
        if ($form->get('answer3')->isClicked() && $correctAnswer === 2) {
            $won = true;
        }
        if ($form->get('answer4')->isClicked() && $correctAnswer === 3) {
            $won = true;
        }
        return array($correctAnswerText, $won);
    }
}
