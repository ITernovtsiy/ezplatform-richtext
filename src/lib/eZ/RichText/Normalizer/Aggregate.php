<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);
/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Normalizer;

use EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Normalizer;

/**
 * Aggregate normalizer converts using configured normalizers in prioritized order.
 */
class Aggregate extends Normalizer
{
    /**
     * An array of normalizers, sorted by priority.
     *
     * @var \EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Normalizer[]
     */
    protected $normalizers = [];

    /**
     * @param \EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Normalizer[] $normalizers An array of Normalizers, sorted by priority
     */
    public function __construct(array $normalizers = [])
    {
        $this->normalizers = $normalizers;
    }

    /**
     * Check if normalizer accepts given $input for normalization.
     *
     * This implementation always returns true.
     *
     * @param string $input
     *
     * @return bool
     */
    public function accept($input)
    {
        return true;
    }

    /**
     * Normalizes given $input by calling aggregated normalizers.
     *
     * @param string $input
     *
     * @return string
     */
    public function normalize($input)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->accept($input)) {
                $input = $normalizer->normalize($input);
            }
        }

        return $input;
    }
}
