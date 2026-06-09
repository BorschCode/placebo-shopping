<?php

namespace App\DataFixtures;

use App\Entity\FakeOrder;
use App\Enum\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixture extends Fixture implements DependentFixtureInterface
{
    private const ADDRESSES = [
        'вул. Хрещатик, 1, кв. 12, Київ',
        'вул. Сумська, 34, Харків',
        'пр. Шевченка, 15, Львів',
        'вул. Дерибасівська, 8, Одеса',
        'пр. Перемоги, 22, Дніпро',
        'вул. Соборна, 11, Запоріжжя',
        'вул. Вінниця, 5, Вінниця',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');
        $statuses = OrderStatus::cases();
        $themeKeys = ['olx', 'autoria', 'experinza'];

        for ($i = 0; $i < 20; $i++) {
            $themeKey = $themeKeys[$i % count($themeKeys)];
            $listingIndex = ($i / count($themeKeys)) % 20;
            /** @var \App\Entity\Listing $listing */
            $listing = $this->getReference('listing_' . $themeKey . '_' . (int)$listingIndex, \App\Entity\Listing::class);

            $buyerIndex = random_int(0, UserFixture::USERS - 1);
            $buyer = $this->getReference('user_' . $buyerIndex, \App\Entity\User::class);

            $order = new FakeOrder();
            $order->setListing($listing);
            $order->setBuyer($buyer);
            $order->setStatus($statuses[array_rand($statuses)]);
            $order->setDeliveryAddress(self::ADDRESSES[array_rand(self::ADDRESSES)]);
            $order->setEstimatedMinutes(random_int(20, 90));
            $order->setCreatedAt(new \DateTimeImmutable('-' . random_int(1, 30) . ' days'));

            $manager->persist($order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixture::class, ListingFixture::class];
    }
}
