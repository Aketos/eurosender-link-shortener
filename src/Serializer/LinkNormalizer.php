<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Domain\Link;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LinkNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Link;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $normalizedLink = $this->normalizer->normalize($object, $format, $context);
        unset($normalizedLink['type']);
        return $normalizedLink;
    }
}
