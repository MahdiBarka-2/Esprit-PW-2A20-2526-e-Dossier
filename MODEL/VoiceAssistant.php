<?php
/**
 * VoiceAssistant Model - Data Structure
 * This class defines the properties for the Vocal Assistant.
 */
class VoiceAssistant {
    public $language;
    public $rate;
    public $pitch;
    public $volume;

    /**
     * Constructor to initialize data
     */
    public function __construct($language = 'en', $rate = 1.1, $pitch = 1.6, $volume = 1.0) {
        $this->language = $language;
        $this->rate = $rate;
        $this->pitch = $pitch;
        $this->volume = $volume;
    }
}
?>
