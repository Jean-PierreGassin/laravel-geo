<?php

namespace JeanPierreGassin\LaravelGeo\Data;

use JsonSerializable;

final readonly class SchemaGraph implements JsonSerializable
{
    private const string SCHEMA_ORG_CONTEXT = 'https://schema.org';

    public function __construct(
        public string $type,
        public string $name,
        public ?string $description,
        public string $url,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            '@context' => self::SCHEMA_ORG_CONTEXT,
            '@type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url,
        ];
    }
}
