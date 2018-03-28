<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);
/**
 * This file contains the ConverterDispatcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRichTextFieldType\eZ\RichText;

use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use DOMDocument;

/**
 * Dispatcher for various converters depending on the XML document namespace.
 */
class ConverterDispatcher
{
    /**
     * Mapping of namespaces to converters.
     *
     * @var \EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Converter[]
     */
    protected $mapping = [];

    /**
     * @param \EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Converter[] $converterMap
     */
    public function __construct($converterMap)
    {
        foreach ($converterMap as $namespace => $converter) {
            $this->addConverter($namespace, $converter);
        }
    }

    /**
     * Adds converter mapping.
     *
     * @param string $namespace
     * @param null|\EzSystems\EzPlatformRichTextFieldType\eZ\RichText\Converter $converter
     */
    public function addConverter($namespace, Converter $converter = null)
    {
        $this->mapping[$namespace] = $converter;
    }

    /**
     * Dispatches DOMDocument to the namespace mapped converter.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     *
     * @param \DOMDocument $document
     *
     * @return \DOMDocument
     */
    public function dispatch(DOMDocument $document)
    {
        $documentNamespace = $document->documentElement->lookupNamespaceURI(null);
        // checking for null as ezxml has no default namespace...
        if ($documentNamespace === null) {
            $documentNamespace = $document->documentElement->lookupNamespaceURI('xhtml');
        }

        foreach ($this->mapping as $namespace => $converter) {
            if ($documentNamespace === $namespace) {
                if ($converter === null) {
                    return $document;
                }

                return $converter->convert($document);
            }
        }

        throw new NotFoundException('Converter', $documentNamespace);
    }
}
