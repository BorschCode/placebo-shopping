<?php

namespace App\DataFixtures;

use App\Entity\Listing;
use App\Enum\ListingStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ListingFixture extends Fixture implements DependentFixtureInterface
{
    private const OLX_TITLES = [
        'iPhone 14 Pro 256GB', 'Samsung Galaxy S23', 'MacBook Air M2', 'Sony PlayStation 5',
        'Квартира 2к, вул. Хрещатик', 'Будинок з ділянкою в Броварах', 'Volkswagen Golf 2019',
        'Toyota Camry 2021 офіційна', 'Nike Air Max 270', 'Adidas Ultraboost 22',
        'Дитяча коляска Chicco', 'Велосипед Trek Marlin 5', 'Диван кутовий IKEA',
        'Холодильник Samsung 2-камерний', 'Ноутбук Lenovo IdeaPad', 'Навушники AirPods Pro 2',
        'Робота: фронтенд розробник', 'Ремонт квартир під ключ', 'Йоркширський тер\'єр',
        'Playstation 4 + 10 ігор',
    ];

    private const AUTORIA_TITLES = [
        'Toyota Camry 3.5 2022 рік', 'BMW 5 Series 2020 дизель', 'Mercedes-Benz C-Class 2019',
        'Honda CR-V 2021 повний привід', 'Volkswagen Tiguan 2020', 'Audi A4 2018 1.8 TFSI',
        'Renault Duster 2021 4x4', 'Skoda Octavia 2022 А8', 'Kia Sportage 2020 автомат',
        'Hyundai Tucson 2021 NX4', 'Ford Focus 2019 1.5 EcoBoost', 'Mazda CX-5 2022',
        'Yamaha MT-07 2021 мотоцикл', 'Honda CBR 600RR 2019', 'Mercedes Sprinter вантажний 2020',
        'Трактор МТЗ-82 2018', 'Автобус Mercedes Sprinter 18 місць', 'BMW R 1250 GS 2021',
        'Запчастини до Toyota Camry V50', 'Шини Michelin 205/55 R16 комплект',
    ];

    private const EXPERINZA_TITLES = [
        'Маргарита велика', 'Піца BBQ з куркою', 'Четири сири', 'Діабло гостра',
        'Класичний чізбургер', 'Подвійний смокі-бургер', 'Курячий бургер теріякі',
        'Філадельфія 8 шт.', 'Каліфорнія 12 шт.', 'Дракон ролл',
        'Кока-Кола 0.5л', 'Свіжий апельсиновий сік', 'Смузі манго-банан',
        'Тірамісу класичне', 'Шоколадний фондан', 'Чізкейк Нью-Йорк',
        'Боул з куркою та авокадо', 'Веганська шаурма', 'Лаваш з телятиною',
        'Гречана каша з грибами',
    ];

    private const LOCATIONS = [
        'Київ', 'Харків', 'Одеса', 'Дніпро', 'Запоріжжя', 'Львів', 'Херсон', 'Вінниця',
    ];

    private const CATEGORY_SLUGS = [
        'olx' => ['olx-electronics','olx-auto','olx-realty','olx-clothing','olx-kids','olx-jobs','olx-services','olx-animals','olx-hobby'],
        'autoria' => ['ar-cars','ar-moto','ar-trucks','ar-special','ar-buses','ar-parts','ar-accessories','ar-water'],
        'experinza' => ['ex-pizza','ex-burgers','ex-sushi','ex-drinks','ex-desserts','ex-vegan','ex-shawarma','ex-healthy'],
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        $this->loadGroup($manager, $faker, 'olx', self::OLX_TITLES, 50, 500000, 50000000);
        $this->loadGroup($manager, $faker, 'autoria', self::AUTORIA_TITLES, 100000, 3000000, 200000000);
        $this->loadGroup($manager, $faker, 'experinza', self::EXPERINZA_TITLES, 5000, 50000, 300000);

        $manager->flush();
    }

    private function loadGroup(
        ObjectManager $manager,
        \Faker\Generator $faker,
        string $themeKey,
        array $titles,
        int $priceMin,
        int $priceMax,
        int $priceMaxOverride,
    ): void {
        $categorySlugs = self::CATEGORY_SLUGS[$themeKey];

        for ($i = 0; $i < 20; $i++) {
            $title = $titles[$i % count($titles)];
            $slug = $themeKey . '-' . $i;
            $numImages = random_int(1, 3);
            $images = [];
            for ($j = 0; $j < $numImages; $j++) {
                $images[] = 'https://picsum.photos/seed/' . $slug . '-' . $j . '/640/480';
            }

            $listing = new Listing();
            $listing->setTitle($title);
            $listing->setDescription($faker->paragraphs(2, true));
            $listing->setPrice(random_int($priceMin, $priceMax));
            $listing->setImages($images);
            $listing->setLocation(self::LOCATIONS[array_rand(self::LOCATIONS)]);
            $listing->setStatus(ListingStatus::Active);
            $listing->setCreatedAt(new \DateTimeImmutable('-' . random_int(1, 60) . ' days'));
            $listing->setUpdatedAt(new \DateTimeImmutable());

            $catSlug = $categorySlugs[$i % count($categorySlugs)];
            $listing->setCategory($this->getReference('cat_' . $catSlug, \App\Entity\Category::class));

            $sellerIndex = random_int(0, UserFixture::USERS - 1);
            $listing->setSeller($this->getReference('user_' . $sellerIndex, \App\Entity\User::class));

            $manager->persist($listing);
            $this->addReference('listing_' . $themeKey . '_' . $i, $listing);
        }
    }

    public function getDependencies(): array
    {
        return [UserFixture::class, CategoryFixture::class];
    }
}
