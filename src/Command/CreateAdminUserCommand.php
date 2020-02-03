<?php

namespace App\Command;

use App\Entity\User;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-admin-user';
    private $entityManger;
    private $passwordEncoder;

    public function __construct(string $name = null, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManger = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('This command create an Admin User')
            ->addArgument('email', InputArgument::REQUIRED, 'Admin user email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password Admin User')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First name Admin User')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last name Admin User')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper("question");
        $question = new ConfirmationQuestion(
            "Confirmer la création de l'utilisateur ? [y/n]",
            false, '/^(y|j)/i'
        );
        if(!$helper->ask($input, $output, $question)){
            return 0;
        }
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');

        $user = new User();
        $hashedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setRole(["ROLE_ADMIN"]);
        $user->setCreatedAt(new \DateTime());
        $user->setLastName($lastName);
        $user->setFirstName($firstName);

        try{
            $this->entityManger->persist($user);
            $this->entityManger->flush();
        }catch (\Exception $exception) {
            $io->error("A error occurred : ".$exception->getMessage());
            return 0;
        }

        $io->success('An new Admin User as been created.');
        return 0;
    }
}
