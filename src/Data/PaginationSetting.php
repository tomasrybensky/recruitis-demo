<?php

namespace App\Data;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationSetting
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $page;
}
