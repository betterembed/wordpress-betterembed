<?php

namespace BetterEmbed\WordPress\Util;

use BetterEmbed\WordPress\Exception\FailedToCreateAttachment;
use BetterEmbed\WordPress\Exception\FailedToDownloadUrl;
use BetterEmbed\WordPress\Exception\InvalidFile;
use BetterEmbed\WordPress\Exception\InvalidUrl;
use Requests_Utility_CaseInsensitiveDictionary;

use function download_url;

class AttachmentHelper
{

    /**
     *
     * @param string $url
     * @param int $parent
     *
     * @throws InvalidUrl if the provided URL isn't a valid URL.
     * @throws FailedToDownloadUrl if getting the HEAD data for the provided URL fails.
     * @throws InvalidFile if the file isn't a valid image file.
     * @throws InvalidFile if the file is too big.
     * @throws FailedToDownloadUrl if downloading the file fails.
     * @throws FailedToCreateAttachment if creating an attachment fails.
     *
     * @return int
     */
    public static function urlToAttachment( string $url, int $parent): int {

        if ($url === '' || ! wp_parse_url($url, PHP_URL_HOST)) {
            throw InvalidUrl::fromUrl($url);
        }

        $ext = self::validateUrl($url);

        //TODO: Filterable, maybe lower in frontend
        $timeout = 10;

        $tempFile = download_url($url, $timeout);

        if (is_wp_error($tempFile)) {
            throw FailedToDownloadUrl::fromWpError($url, $tempFile);
        }

        $fileArray = array(
            'tmp_name' => $tempFile,
            'name'     => self::buildFilename($ext, $parent),
        );

        $postData = array(
            'post_title' => sprintf(
                /* translators: %s: post id. */
                __('Thumbnail for BetterEmbed "%s"', 'betterembed'),
                $parent
            ),
        );

        //TODO: Limit image sizes to what we actually need for the embeds

        // Deletes the temporary file.
        $attachmentId = media_handle_sideload($fileArray, $parent, '', $postData);

        if (is_wp_error($attachmentId)) {
            throw FailedToCreateAttachment::fromWpError($url, $attachmentId);
        }

        return $attachmentId;
    }

    protected static function buildFilename( string $ext, int $parentId): string {
        return 'betterembed_thumbnail_' . $parentId . '.' . $ext;
    }

    /**
     * @param string $url
     *
     *
     *
     * @return string
     */
    protected static function validateUrl( string $url): string {

        /** @var false|array|Requests_Utility_CaseInsensitiveDictionary $headers */
        $headers = wp_get_http_headers($url);

        if ($headers === false) {
            throw new FailedToDownloadUrl(
                sprintf('Could not get HTTP headers of url "%s"', $url)
            );
        }

        $contentType = $headers->offsetGet('content-type');

        $ext = self::getExtensionFromMime(
            $contentType ?? ''
        );
        if (is_null($ext)) {
            throw InvalidFile::fromContentType($url, $contentType);
        }

        $contentLength = intval($headers->offsetGet('content-length'));
        $maxUploadSize = wp_max_upload_size();
        if (! is_null($contentLength) && $contentLength > $maxUploadSize) {
            throw InvalidFile::fromFileSizeType($url, $contentLength, $maxUploadSize);
        }

        return $ext;
    }

    /**
     * Returns Image file extension by mime type
     *
     * @see wp_check_filetype_and_ext()
     *
     * @param string $mime
     * @return string|null
     */
    protected static function getExtensionFromMime( string $mime) {

        $mimes = array(
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/bmp'  => 'bmp',
            'image/tiff' => 'tif',
        );

        return array_key_exists($mime, $mimes) ? $mimes[ $mime ] : null;
    }
}
