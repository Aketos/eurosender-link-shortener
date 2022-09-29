<?php declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /** @throws ValidatorException */
    public function getParameters(Request $request): array
    {
        $parameters = $this->getRequestParameters($request);

        $this->validateParameters($parameters);

        return $parameters;
    }

    public function getParameter(Request $request, string $parameterName)
    {
        return $this->getParameters($request)[$parameterName] ?? null;
    }

    private function validateParameters(array $parameters): void
    {
        $violations = $this->validator->validate($parameters, $this->getRequestConstraints());

        if ($violations->count() > 0) {
            $messages = array_map(
                static function (ConstraintViolationInterface $violation) {
                    return sprintf('[%s] error on property %s: %s',
                        $violation->getCode(),
                        $violation->getPropertyPath(),
                        $violation->getMessage()
                    );
                },
                iterator_to_array($violations)
            );

            throw new ValidatorException(implode(', ', $messages));
        }
    }

    abstract protected function getRequestParameters(Request $request): array;

    abstract protected function getRequestConstraints(): Constraint;
}
