<?php

namespace App\Twig\Runtime;

use App\Repository\HoraireRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    private HoraireRepository $horaireRepository;
    public function __construct(HoraireRepository $horaireRepository)
    {
        $this->horaireRepository = $horaireRepository;
    }

    public function getHoraires() : array
    {
        return $this->horaireRepository->findAll();
    }
}
