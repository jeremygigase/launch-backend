<?php

namespace App\Entity;

interface CreatedDateInterface
{
    public function setCreated(\DateTimeInterface $published): CreatedDateInterface;
}