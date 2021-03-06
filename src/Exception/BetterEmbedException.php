<?php

namespace BetterEmbed\WordPress\Exception;

use Throwable;

/**
 * This is a "marker interface" to mark all the exception that come with this plugin with this one interface.
 *
 * This allows you to not only catch individual exceptions, but also catch "all exceptions from BetterEmbed".
 */
interface BetterEmbedException extends Throwable
{

}
