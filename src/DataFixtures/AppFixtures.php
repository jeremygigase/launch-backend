<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\Image;
use App\Entity\Friend;
use App\Entity\Score;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var \Faker\Factory
     */
    private $faker;
    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Piotr Jura',
            'password' => 'secret123#'
        ],
        [
            'username' => 'john_doe',
            'email' => 'john@blog.com',
            'name' => 'John Doe',
            'password' => 'secret123#'
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob@blog.com',
            'name' => 'Rob Smith',
            'password' => 'secret123#'
        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'name' => 'Jenny Rowling',
            'password' => 'secret123#'
        ]
    ];
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadUsers($manager);
        $this->loadTasks($manager);
        //$this->loadComments($manager);
    }

    public function loadTasks(ObjectManager $manager)
    {
        $user = $this->getReference('user_admin');

        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setTskText($this->faker->realText(30));
            $task->setCreated($this->faker->dateTimeThisYear);
            $task->setTskStatus($this->faker->realText());


            $manager->persist($task);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
            $user = new User();
            $user->setUsrUsername('admin');
            $user->setUsrEmail('admin@launch.com');
            $user->setUsrFirstname('Jeremy');
            $user->setUsrLastname('Gigase');

            $user->setUsrPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'foo'
                )
            );

            $this->addReference('user_admin', $user);

            $manager->persist($user);
            $manager->flush();
    }
}
