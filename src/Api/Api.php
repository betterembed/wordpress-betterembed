<?php

namespace BetterEmbed\WordPress\Api;

use BetterEmbed\WordPress\Exception\FailedToDownloadUrl;
use BetterEmbed\WordPress\Exception\InvalidUrl;
use BetterEmbed\WordPress\Exception\JsonException as BetterEmbedJsonException;
use BetterEmbed\WordPress\Model\Item;
use BetterEmbed\WordPress\Model\ProblemDetails;

class Api
{

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $apiKey;

    /**
     * @throws InvalidUrl for invalid Endpoint URLs.
     *
     * @param string $endpoint
     * @param string $apiKey
     */
    public function __construct( string $endpoint, string $apiKey = '' ) {

        if (filter_var($endpoint, FILTER_VALIDATE_URL) === false) {
            throw InvalidUrl::fromUrl($endpoint);
        }

        $this->endpoint = $endpoint;
        $this->apiKey   = $apiKey;
    }


    /**
     * @param string $embedUrl
     *
     * @throws FailedToDownloadUrl
     *
     * @return Item
     */
    public function getItem( string $embedUrl): Item {

        $url = add_query_arg(
            array( 'url' => $embedUrl ),
            $this->endpoint
        );

        $request = wp_remote_get($url);
        if (is_wp_error($request)) {
            throw FailedToDownloadUrl::fromWpError($url, $request);
        }

        $code = wp_remote_retrieve_response_code($request);
        $body = wp_remote_retrieve_body($request);
        if ($code === '' || $body === '') {
            throw FailedToDownloadUrl::forEmpty($url);
        }

        if ($code !== 200) {
            try {
                $problemDetails = $this->buildProblemDetails($body);
            } catch (BetterEmbedJsonException $exception) {
                throw FailedToDownloadUrl::forVarious($url);
            }

            throw FailedToDownloadUrl::fromProblemDetails($url, $problemDetails);
        }

        try {
            $item = $this->buildItem($body);
        } catch (BetterEmbedJsonException $exception) {
            throw FailedToDownloadUrl::forVarious($url);
        }

        return $item;
    }

    /**
     * @param string $body
     *
     * @throws BetterEmbedJsonException
     *
     * @return array
     */
    protected function decodeJsonArray( string $body): array {

        $data = json_decode($body, true, 2);

        if (is_null($data) || !is_array($data)) {
            throw BetterEmbedJsonException::forVarious();
        }

        return $data;
    }

    /**
     * @param string $body
     *
     * @throws BetterEmbedJsonException
     *
     * @return ProblemDetails
     */
    protected function buildProblemDetails( string $body ): ProblemDetails {

        $data = wp_parse_args(
            $this->decodeJsonArray($body),
            array(
                'title'  => '',
                'status' => '',
                'detail' => '',
            )
        );

        return new ProblemDetails(
            $data['title'] ?? '',
            $data['status'] ?? '',
            $data['detail'] ?? ''
        );
    }

    /**
     * @param string $body
     *
     * @throws BetterEmbedJsonException
     *
     * @return Item
     */
    protected function buildItem( string $body): Item {

        $data = wp_parse_args(
            $this->decodeJsonArray($body),
            array(
                'url'          => '',
                'itemType'     => '',
                'title'        => '',
                'body'         => '',
                'thumbnailUrl' => '',
                'authorName'   => '',
                'authorUrl'    => '',
                'publishedAt'  => '',
            )
        );

        return new Item(
            $data['url'],
            $data['itemType'],
            $data['title'],
            $data['body'],
            $data['thumbnailUrl'],
            $data['authorName'],
            $data['authorUrl'],
            $data['publishedAt']
        );
    }
}
