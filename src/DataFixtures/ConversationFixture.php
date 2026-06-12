<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConversationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $themeKeys = ['olx', 'autoria', 'experinza'];
        $convIndex = 0;

        foreach ($themeKeys as $themeKey) {
            for ($i = 0; $i < 5; $i++) {
                $listingRef = 'listing_' . $themeKey . '_' . $i;
                /** @var \App\Entity\Listing $listing */
                $listing = $this->getReference($listingRef, \App\Entity\Listing::class);
                $seller = $listing->getSeller();

                // Pick a buyer that isn't the seller
                do {
                    $buyerIndex = random_int(0, UserFixture::USERS - 1);
                    $buyer = $this->getReference('user_' . $buyerIndex, \App\Entity\User::class);
                } while ($buyer->getId() === $seller->getId());

                $conv = new Conversation();
                $conv->setListing($listing);
                $conv->addParticipant($seller);
                $conv->addParticipant($buyer);
                $conv->setCreatedAt(new \DateTimeImmutable('-' . random_int(1, 30) . ' days'));

                $manager->persist($conv);
                $this->addReference('conversation_' . $convIndex, $conv);
                $convIndex++;
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixture::class, ListingFixture::class];
    }
}
