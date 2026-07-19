<?php

namespace JeanPierreGassin\LaravelGeo\Contracts;

use JeanPierreGassin\LaravelGeo\Data\SchemaGraph;
use JeanPierreGassin\LaravelGeo\Exceptions\SchemaGraphEncodingException;

interface SchemaGraphRenderer
{
    /**
     * Render the schema.org graph as a JSON-LD document, safely escaped for
     * embedding inside a <script type="application/ld+json"> tag.
     *
     * @throws SchemaGraphEncodingException
     */
    public function render(SchemaGraph $graph): string;
}
