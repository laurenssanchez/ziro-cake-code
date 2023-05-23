<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML;

class VoiceResponse extends TwiML {
    /**
     * VoiceResponse constructor.
     */
    public function __construct() {
        parent::__construct('Response', null);
    }

    /**
     * Add Connect child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Connect Child element.
     */
    public function connect($attributes = array()) {
        return $this->nest(new Voice\Connect($attributes));
    }

    /**
     * Add Dial child.
     *
     * @param string $number Phone number to dial
     * @param array $attributes Optional attributes
     * @return Voice\Dial Child element.
     */
    public function dial($number = null, $attributes = array()) {
        return $this->nest(new Voice\Dial($number, $attributes));
    }

    /**
     * Add Echo child.
     *
     * @return Voice\Echo_ Child element.
     */
    public function echo_() {
        return $this->nest(new Voice\Echo_());
    }

    /**
     * Add Enqueue child.
     *
     * @param string $name Friendly name
     * @param array $attributes Optional attributes
     * @return Voice\Enqueue Child element.
     */
    public function enqueue($name = null, $attributes = array()) {
        return $this->nest(new Voice\Enqueue($name, $attributes));
    }

    /**
     * Add Gather child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Gather Child element.
     */
    public function gather($attributes = array()) {
        return $this->nest(new Voice\Gather($attributes));
    }

    /**
     * Add Hangup child.
     *
     * @return Voice\Hangup Child element.
     */
    public function hangup() {
        return $this->nest(new Voice\Hangup());
    }

    /**
     * Add Leave child.
     *
     * @return Voice\Leave Child element.
     */
    public function leave() {
        return $this->nest(new Voice\Leave());
    }

    /**
     * Add Pause child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Pause Child element.
     */
    public function pause($attributes = array()) {
        return $this->nest(new Voice\Pause($attributes));
    }

    /**
     * Add Play child.
     *
     * @param string $url Media URL
     * @param array $attributes Optional attributes
     * @return Voice\Play Child element.
     */
    public function play($url = null, $attributes = array()) {
        return $this->nest(new Voice\Play($url, $attributes));
    }

    /**
     * Add Queue child.
     *
     * @param string $name Queue name
     * @param array $attributes Optional attributes
     * @return Voice\Queue Child element.
     */
    public function queue($name, $attributes = array()) {
        return $this->nest(new Voice\Queue($name, $attributes));
    }

    /**
     * Add Record child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Record Child element.
     */
    public function record($attributes = array()) {
        return $this->nest(new Voice\Record($attributes));
    }

    /**
     * Add Redirect child.
     *
     * @param string $url Redirect URL
     * @param array $attributes Optional attributes
     * @return Voice\Redirect Child element.
     */
    public function redirect($url, $attributes = array()) {
        return $this->nest(new Voice\Redirect($url, $attributes));
    }

    /**
     * Add Reject child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Reject Child element.
     */
    public function reject($attributes = array()) {
        return $this->nest(new Voice\Reject($attributes));
    }

    /**
     * Add Say child.
     *
     * @param string $message Message to say
     * @param array $attributes Optional attributes
     * @return Voice\Say Child element.
     */
    public function say($message, $attributes = array()) {
        return $this->nest(new Voice\Say($message, $attributes));
    }

    /**
     * Add Sms child.
     *
     * @param string $message Message body
     * @param array $attributes Optional attributes
     * @return Voice\Sms Child element.
     */
    public function sms($message, $attributes = array()) {
        return $this->nest(new Voice\Sms($message, $attributes));
    }

    /**
     * Add Pay child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Pay Child element.
     */
    public function pay($attributes = array()) {
        return $this->nest(new Voice\Pay($attributes));
    }

    /**
     * Add Prompt child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Prompt Child element.
     */
    public function prompt($attributes = array()) {
        return $this->nest(new Voice\Prompt($attributes));
    }

    /**
     * Add Start child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Start Child element.
     */
    public function start($attributes = array()) {
        return $this->nest(new Voice\Start($attributes));
    }

    /**
     * Add Stop child.
     *
     * @return Voice\Stop Child element.
     */
    public function stop() {
        return $this->nest(new Voice\Stop());
    }

    /**
     * Add Refer child.
     *
     * @param array $attributes Optional attributes
     * @return Voice\Refer Child element.
     */
    public function refer($attributes = array()) {
        return $this->nest(new Voice\Refer($attributes));
    }
}