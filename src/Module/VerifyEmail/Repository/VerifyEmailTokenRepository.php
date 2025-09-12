<?php

namespace App\Module\VerifyEmail\Repository;

use App\Repository\AbstractRepository;
use App\Entity\VerifyEmailToken;
use Doctrine\Persistence\ManagerRegistry;

class VerifyEmailTokenRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerifyEmailToken::class);
    }
}
