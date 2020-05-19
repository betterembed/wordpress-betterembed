<?php


namespace BetterEmbed\WordPress\Model;


class ProblemDetails {

	protected $title;
    protected $status;
    protected $detail;

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
