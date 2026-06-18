<?php
namespace App\Command;

use App\Entity\User;
use App\Entity\Enseignant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-enseignant')]
class CreateEnseignantCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $enseignant = new Enseignant();
        $enseignant->setNom('Dupont');
        $enseignant->setPrenom('Jean');
        $enseignant->setEmail('enseignant@soutenancepro.tg');
        $enseignant->setSpecialite('Informatique');
        $this->em->persist($enseignant);

        $user = new User();
        $user->setEmail('enseignant@soutenancepro.tg');
        $user->setRoles(['ROLE_ENSEIGNANT']);
        $user->setPassword($this->hasher->hashPassword($user, 'enseignant123'));
        $this->em->persist($user);

        $this->em->flush();
        $output->writeln('Enseignant créé : enseignant@soutenancepro.tg / enseignant123');
        return Command::SUCCESS;
    }
}