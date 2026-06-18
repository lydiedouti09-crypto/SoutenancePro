<?php
namespace App\Twig;

use App\Repository\EnseignantRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private Security $security,
        private EnseignantRepository $enseignantRepo
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_enseignant_photo', [$this, 'getCurrentEnseignantPhoto']),
        ];
    }

    public function getCurrentEnseignantPhoto(): ?string
    {
        $user = $this->security->getUser();
        if (!$user || $this->security->isGranted('ROLE_ADMIN')) {
            return null;
        }
        $enseignant = $this->enseignantRepo->findOneBy(['email' => $user->getEmail()]);
        return $enseignant?->getPhoto();
    }
}