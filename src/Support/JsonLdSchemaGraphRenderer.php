<?php

namespace JeanPierreGassin\LaravelGeo\Support;

use JeanPierreGassin\LaravelGeo\Contracts\SchemaGraphRenderer;
use JeanPierreGassin\LaravelGeo\Data\SchemaGraph;
use JeanPierreGassin\LaravelGeo\Exceptions\SchemaGraphEncodingException;
use JsonException;

class JsonLdSchemaGraphRenderer implements SchemaGraphRenderer
{
    /**
     * JSON_HEX_TAG escapes < and > so the payload cannot break out of the
     * surrounding <script> tag; JSON_THROW_ON_ERROR surfaces encoding failures.
     */
    private const int ENCODING_FLAGS = JSON_HEX_TAG | JSON_THROW_ON_ERROR;

    /**
     * @throws SchemaGraphEncodingException
     */
    public function render(SchemaGraph $graph): string
    {
        try {
            return json_encode($graph, self::ENCODING_FLAGS);
        } catch (JsonException $exception) {
            throw new SchemaGraphEncodingException(
                'Failed to encode the schema.org graph as JSON-LD.',
                previous: $exception,
            );
        }
    }
}
