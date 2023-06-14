<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Quiz\SongQuiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author  Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2023 DocCheck Community GmbH
 */
class SongSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /** @var SongQuiz $quiz */
        $quiz = $options['data']['answers'];
        if (!is_null($quiz)){
            $answers = $quiz->getAnswers();
        }

        $builder
            ->add('answer1', SubmitType::class, [
                'label' => $answers[0],
                'attr' => [
                    'class' => 'answer-button',
                ],
            ])
            ->add('answer2', SubmitType::class, [
                'label' => $answers[1],
                'attr' => [
                    'class' => 'answer-button',
                ],
            ])
            ->add('answer3', SubmitType::class, [
                'label' => $answers[2],
                'attr' => [
                    'class' => 'answer-button',
                ],
            ])
            ->add('answer4', SubmitType::class, [
                'label' => $answers[3],
                'attr' => [
                    'class' => 'answer-button',
                ],
            ])
            ->add('correctAnswer', HiddenType::class, ['data' => $answers[3]])
            ->add('correctAnswerText', HiddenType::class, ['data' => $answers[3]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'answers' => null, // Add a default value for the 'answers' option
        ]);
    }
}