<?php


namespace App\Entity\Traits;

use App\Entity\User;

interface TimestampableInterface
{
    public function setCreatedAt(\DateTimeImmutable $createdAt): void;
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void;
    public function setCreatedBy(User $user): void;
    public function setUpdatedBy(User $user): void;
}