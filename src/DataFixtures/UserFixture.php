<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(1, 'main_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('test@example.com', $i));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'authenticate'));
            $user->setRoles([]);

            return $user;
        });

        $manager->flush();
    }
}
