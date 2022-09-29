<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Domain\Tracker;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TrackerNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Tracker;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        /** @var Tracker $object */
        $normalizedTracker = $this->normalizer->normalize($object, $format, $context);
        $normalizedTracker['redirectedAt'] = $object->getRedirectedAt()->getTimestamp();
        unset($normalizedTracker['type']);
        return $normalizedTracker;
    }
}
