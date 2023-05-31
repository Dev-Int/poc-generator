<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class CustomerFixtures extends Fixture
{
    private const VALID_ADDRESS_NAMES = ['main', 'work', 'parents', 'holiday'];

    public function load(ObjectManager $manager): void
    {
        $users = $this->getCustomers(100000);
        foreach ($users as $key => $user) {
            $manager->persist($user);

            if (0 === ($key % 5000)) {
                $manager->flush();
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function getCustomers(int $count): iterable
    {
        $faker = Factory::create('fr');

        for ($iterUser = 0; $iterUser <= $count; ++$iterUser) {
            $user = (new Customer())
                ->setLastName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setEmail($faker->email())
                ->setCreatedAt(new \DateTimeImmutable(
                    '2023-05-'.random_int(1,31)))
            ;

            for ($iterAddress = 0; $iterAddress <= random_int(1, 3); ++$iterAddress) {
                $address = (new Address())
                    ->setName(self::VALID_ADDRESS_NAMES[$iterAddress])
                    ->setStreet($faker->streetAddress())
                    ->setZipCode($faker->postcode())
                    ->setTown($faker->city())
                ;
                $user->addAddress($address);
            }

            yield $user;
        }
    }
}
