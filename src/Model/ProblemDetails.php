<?php

namespace BetterEmbed\WordPress\Model;

class ProblemDetails
{

    /** @var string */
    protected $title;

    /** @var int  */
    protected $status;

    /** @var string  */
    protected $detail;


    public function __construct( string $title, int $status, string $detail ) {
        $this->title  = $title;
        $this->status = $status;
        $this->detail = $detail;
    }


    /**
     * @return string
     */
    public function title(): string {
        return $this->title;
    }

    /**
     * @return int
     */
    public function status(): int {
        return $this->status;
    }

    /**
     * @return string
     */
    public function detail(): string {
        return $this->detail;
    }
}
