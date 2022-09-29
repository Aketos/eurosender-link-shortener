<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

class ShortenLinkActionValidator extends AbstractRequestValidator
{

    protected function getRequestParameters(Request $request): array
    {
        return [
            'link' => $request->get('link')
        ];
    }

    protected function getRequestConstraints(): Constraint
    {
        return new Collection([
            'link' => [
                new NotBlank(),
                new Type(['type' => 'string']),
            ]
        ]);
    }
}