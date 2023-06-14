<?php

namespace App\Controller;

use App\Service\SongService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpertQuizController extends AbstractController
{
    #[Route('/quiz/expert/song', name: 'app_quiz_expert_song_search')]
    public function index(Request $request, SongService $songService): Response
    {
        $gapQuiz = $songService->getSongQuiz(true);

        $form = $this->createForm(SongSearchFormType::class, ['answers' => $gapQuiz]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($correctAnswerText, $won) = $this->evaluate($form);

            if ($won){
                return $this->redirectToRoute('app_quiz_expert_album_search');
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

    #[Route('/quiz/expert/album', name: 'app_quiz_expert_album_search')]
    public function albumSearch(Request $request, SongService $songService): Response
    {
        $album = $songService->getAlbumQuiz(true);

        $form = $this->createForm(SongSearchFormType::class, ['answers' => $album]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            list($correctAnswerText, $won) = $this->evaluate($form);

            if ($won){
                return $this->redirectToRoute('app_quiz_expert_gap_search');
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

    #[Route('/quiz/expert/gap', name: 'app_quiz_expert_gap_search')]
    public function songGap(Request $request, SongService $songService): Response
    {
        $rectangleImageUrls = [
            '/path/to/image1.jpg',
            '/path/to/image2.jpg',
            '/path/to/image3.jpg',
        ];

        $buttons = [
            [
                'imageUrl' => '/path/to/button1.jpg',
                'alt' => 'Button 1',
            ],
            [
                'imageUrl' => '/path/to/button2.jpg',
                'alt' => 'Button 2',
            ],
            [
                'imageUrl' => '/path/to/button3.jpg',
                'alt' => 'Button 3',
            ],
            [
                'imageUrl' => '/path/to/button4.jpg',
                'alt' => 'Button 4',
            ],
        ];

        $form = $this->createForm(CoverFormType::class, ['answers' => []]);
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
