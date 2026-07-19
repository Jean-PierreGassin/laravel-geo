<?php

namespace JeanPierreGassin\LaravelGeo\Enums;

/**
 * Broad behaviour class of a generative-engine crawler. The distinction matters
 * for GEO: retrieval and agent fetchers can send referral traffic and produce
 * citations, whereas training crawlers only ingest content into a model and
 * return no visible attribution. Branch on the type to decide how much to
 * invest in a given visit.
 */
enum GenerativeEngineType: string
{
    /**
     * Bulk crawler collecting content to train a foundation model. Sends no
     * users and produces no citations.
     */
    case Training = 'training';

    /**
     * Retrieval crawler backing an answer engine's live index. Answers built
     * from it can cite and link back to the page.
     */
    case Search = 'search';

    /**
     * On-demand fetcher acting for a single user prompt (a "user visited via
     * the assistant" request), typically the most citation- and referral-rich.
     */
    case Agent = 'agent';
}
