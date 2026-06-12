<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Enum\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    private const CATEGORIES = [
        Theme::Olx->value => [
            ['name' => 'Електроніка',    'slug' => 'olx-electronics',  'icon' => '📱'],
            ['name' => 'Авто',           'slug' => 'olx-auto',         'icon' => '🚗'],
            ['name' => 'Нерухомість',    'slug' => 'olx-realty',       'icon' => '🏠'],
            ['name' => 'Одяг і взуття',  'slug' => 'olx-clothing',     'icon' => '👗'],
            ['name' => 'Дитячий світ',   'slug' => 'olx-kids',         'icon' => '🧸'],
            ['name' => 'Робота',         'slug' => 'olx-jobs',         'icon' => '💼'],
            ['name' => 'Послуги',        'slug' => 'olx-services',     'icon' => '🔧'],
            ['name' => 'Тварини',        'slug' => 'olx-animals',      'icon' => '🐾'],
            ['name' => 'Хобі та спорт',  'slug' => 'olx-hobby',        'icon' => '⚽'],
        ],
        Theme::Autoria->value => [
            ['name' => 'Легкові',        'slug' => 'ar-cars',          'icon' => '🚗'],
            ['name' => 'Мотоцикли',      'slug' => 'ar-moto',          'icon' => '🏍️'],
            ['name' => 'Вантажівки',     'slug' => 'ar-trucks',        'icon' => '🚛'],
            ['name' => 'Спецтехніка',    'slug' => 'ar-special',       'icon' => '🚜'],
            ['name' => 'Автобуси',       'slug' => 'ar-buses',         'icon' => '🚌'],
            ['name' => 'Запчастини',     'slug' => 'ar-parts',         'icon' => '🔩'],
            ['name' => 'Аксесуари',      'slug' => 'ar-accessories',   'icon' => '🎿'],
            ['name' => 'Водний транспорт', 'slug' => 'ar-water',       'icon' => '⛵'],
        ],
        Theme::Experinza->value => [
            ['name' => 'Піца',           'slug' => 'ex-pizza',         'icon' => '🍕'],
            ['name' => 'Бургери',        'slug' => 'ex-burgers',       'icon' => '🍔'],
            ['name' => 'Суші',           'slug' => 'ex-sushi',         'icon' => '🍣'],
            ['name' => 'Напої',          'slug' => 'ex-drinks',        'icon' => '🥤'],
            ['name' => 'Десерти',        'slug' => 'ex-desserts',      'icon' => '🍰'],
            ['name' => 'Веганське',      'slug' => 'ex-vegan',         'icon' => '🥗'],
            ['name' => 'Піта / Шаурма', 'slug' => 'ex-shawarma',      'icon' => '🌯'],
            ['name' => 'Здорове',        'slug' => 'ex-healthy',       'icon' => '🥙'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $themeValue => $cats) {
            $theme = Theme::from($themeValue);
            foreach ($cats as $data) {
                $cat = new Category();
                $cat->setName($data['name']);
                $cat->setSlug($data['slug']);
                $cat->setIcon($data['icon']);
                $cat->setThemeType($theme);
                $manager->persist($cat);
                $this->addReference('cat_' . $data['slug'], $cat);
            }
        }
        $manager->flush();
    }
}
