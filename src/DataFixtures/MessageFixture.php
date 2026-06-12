<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixture extends Fixture implements DependentFixtureInterface
{
    private const BUYER_PHRASES = [
        'Привіт! Чи ще актуально?',
        'Добрий день, яка мінімальна ціна?',
        'Можна подивитись сьогодні?',
        'Торг доречний?',
        'Скільки в наявності?',
        'Чи є доставка?',
        'Напишіть більше деталей, будь ласка.',
    ];

    private const SELLER_PHRASES = [
        'Так, актуально!',
        'Ціна фіксована, без торгу.',
        'Так, можна сьогодні після 18:00.',
        'Торг при огляді.',
        'Є в наявності, пишіть.',
        'Доставка по Новій Пошті.',
        'Звертайтесь, відповім на всі питання.',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        for ($i = 0; $i < 15; $i++) {
            /** @var \App\Entity\Conversation $conv */
            $conv = $this->getReference('conversation_' . $i, \App\Entity\Conversation::class);
            $participants = $conv->getParticipants()->toArray();

            $count = random_int(3, 7);
            $time = $conv->getCreatedAt();

            for ($j = 0; $j < $count; $j++) {
                $isBuyer = $j % 2 === 0;
                $sender = $participants[$isBuyer ? 1 : 0];
                $phrases = $isBuyer ? self::BUYER_PHRASES : self::SELLER_PHRASES;

                $message = new Message();
                $message->setConversation($conv);
                $message->setSender($sender);
                $message->setContent($phrases[array_rand($phrases)]);
                $message->setIsRead($j < $count - 1);
                $time = $time->modify('+' . random_int(5, 60) . ' minutes');
                $message->setCreatedAt($time);

                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ConversationFixture::class];
    }
}
