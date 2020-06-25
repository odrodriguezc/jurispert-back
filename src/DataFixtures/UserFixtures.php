<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\AbstractFixtures;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends AbstractFixtures
{

    protected UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder =  $encoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 5, function (User $user, $u) {
            $user
                ->setEmail("user$u@gmail.com")
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setPassword($this->encoder->encodePassword($user, '12345'))
                ->setPost('Assistant')
                ->setCreatedAt($this->faker->dateTimeBetween('- 6 months'))
                ->setUpdatedAt($this->faker->dateTimeBetween('- 2 months'))
                ->setRoles(['ROLE_USER']);
        });

        //ADMIN
        $admin = new User();
        $admin
            ->setEmail('admin@gmail.com')
            ->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setPassword($this->encoder->encodePassword($admin, '12345'))
            ->setPost('Gerant')
            ->setCreatedAt($this->faker->dateTimeBetween('- 6 months'))
            ->setUpdatedAt($this->faker->dateTimeBetween('- 2 months'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->flush();
    }
}
