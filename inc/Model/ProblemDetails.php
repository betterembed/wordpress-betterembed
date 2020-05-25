<?php

namespace BetterEmbed\WordPress\Model;

class ProblemDetails
{

    protected $title;
    protected $status;
    protected $detail;

    /**
     * ProblemDetails constructor.
     *
     * @param $title
     * @param $status
     * @param $detail
     */
    public function __construct( $title, $status, $detail ) {
        $this->title  = $title;
        $this->status = $status;
        $this->detail = $detail;
    }


    /**
     * @return string
     */
    public function title() {
        return $this->title;
    }

    /**
     * @return int
     */
    public function status() {
        return $this->status;
    }

    /**
     * @return string
     */
    public function detail() {
        return $this->detail;
    }
}
