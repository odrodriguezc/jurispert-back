<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Project;
use App\Entity\Customer;
use App\Entity\Participation;
use App\DataFixtures\AbstractFixtures;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends AbstractFixtures
{

    protected UserPasswordEncoderInterface $encoder;

    protected $stages = ['PRE-NEGOTIATION', 'COUR-NEGOTIATIONS', 'TRIAL-INSTRUCTIONS', 'TRIAL-ALEGATIONS', 'TRIAL-JUDGMENT'];

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder =  $encoder;
    }

    public function loadData(ObjectManager $manager)
    {
        //USERS
        $this->createMany(User::class, 5, function (User $user, $u) {
            $user
                ->setEmail("user$u@gmail.com")
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setPassword($this->encoder->encodePassword($user, '12345'))
                ->setPost($this->faker->jobTitle)
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


        //CUSTOMER
        $this->createMany(Customer::class, 10, function (Customer $customer) {
            $customer
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->safeEmail())
                ->setCompany($this->faker->company())
                ->setAddress($this->faker->address());
        });

        //PROJECT
        $this->createMany(Project::class, 50, function (Project $project) {

            $createdAt = $this->faker->dateTimeBetween('- 6 months');

            $project
                ->setTitle($this->faker->catchPhrase())
                ->setShortDescription($this->faker->words(10, true))
                ->setDescription($this->faker->markdownP())
                ->setCreatedAt($createdAt)
                ->setDeadline($createdAt->modify('+' . mt_rand(0, 3) . 'months'))
                ->setAdversary($this->faker->company())
                ->setOwner($this->getRandomReference(User::class))
                ->setStages($this->stages)
                ->setStatus($this->faker->randomElement($this->stages))
                ->setCategory('PROTOTYPE')
                ->addCustomer($this->getRandomReference(Customer::class));
        });

        //TASKS
        $this->createMany(Task::class, 50, function (Task $task) {
            /** @var Project */
            $project = $this->getRandomReference(Project::class);
            /** @var \DateTime */
            $createdAt = $project->getCreatedAt();
            $task
                ->setTitle($this->faker->catchPhrase())
                ->setDescription($this->faker->markdownP())
                ->setCreatedAt($createdAt->modify('+' . mt_rand(0, 5) . 'days'))
                ->setDeadline($createdAt->modify('+' . mt_rand(3, 15) . 'days'))
                ->setProject($project)
                ->setCompleted($this->faker->boolean());
        });

        //EVENTS
        $this->createMany(Event::class, 50, function (Event $event) {

            /** @var Project */
            $project = $this->getRandomReference(Project::class);
            /** @var \DateTime */
            $createdAt = $project->getCreatedAt();
            $event
                ->setTitle($this->faker->catchPhrase())
                ->setDescription($this->faker->markdownP())
                ->setAddress($this->faker->address())
                ->setCreatedAt($createdAt->modify('+' . mt_rand(0, 5) . 'days'))
                ->setDate($createdAt->modify('+' . mt_rand(3, 15) . 'days'))
                ->setProject($project);
        });

        //PARTICIPATIONS
        $this->createMany(Participation::class, 50, function (Participation $participaion) {
            $role =  ($this->faker->boolean()) ? 'VIEWER' : 'MANAGER';

            $participaion
                ->setProject($this->getRandomReference(Project::class))
                ->setRole($role)
                ->setUser($this->getRandomReference(User::class));
        });



        $manager->flush();
    }
}
