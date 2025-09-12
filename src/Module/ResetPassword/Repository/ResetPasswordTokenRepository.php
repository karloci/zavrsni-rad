<?php

namespace App\Module\ResetPassword\Repository;

use App\Repository\AbstractRepository;
use App\Entity\ResetPasswordToken;
use Doctrine\Persistence\ManagerRegistry;

class ResetPasswordTokenRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordToken::class);
    }
}
