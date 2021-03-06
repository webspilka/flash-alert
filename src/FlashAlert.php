<?php namespace Webspilka\FlashAlert;

use Illuminate\Contracts\Config\Repository;

class FlashAlert {

    /**
     * Added notifications
     *
     * @var array
     */
    protected $notifications = [];

    /**
     * Illuminate Session
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * FlashAlert config
     *
     * @var Repository|Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Constructor
     *
     * @param \Illuminate\Session\SessionManager $session
     * @param Repository|Illuminate\Config\Repository $config
     *
     * @internal param \Illuminate\Session\SessionManager $session
     */
    public function __construct(\Illuminate\Session\SessionManager $session, Repository $config) {
        $this->session = $session;
        $this->config = $config;
    }

    /**
     * Render the notifications' script tag
     *
     * @return string
     * @internal param bool $flashed Whether to get the
     *
     */
    public function render() {
        $notifications = $this->session->get('flash::alerts');
        if(!$notifications) $notifications = [];

        $output = '<script type="text/javascript">';
        $lastConfig = [];
        foreach($notifications as $notification) {

            $config = $this->config->get('flashalert.options');

            if(count($notification['options']) > 0) {
                // Merge user supplied options with default options
                $config = array_merge($config, $notification['options']);
            }

            // Config persists between toasts
            if($config != $lastConfig) {
                $output .= 'flashalert.options = ' . json_encode($config) . ';';   
                $lastConfig = $config;
            }

            // FlashAlert output
            $output .= 'flashalert.' . $notification['type'] . "('" . str_replace("'", "\\'", str_replace(['&lt;', '&gt;'], ['<', '>'], e($notification['text']))) . "'" . (isset($notification['title']) ? ", '" . str_replace("'", "\\'", htmlentities($notification['title'])) . "'" : null) . ');';
        }
        $output .= '</script>';

        $this->clear();
        return $output;
    }

    /**
     * Add a notification
     *
     * @param string $type Could be error, info, success, or warning.
     * @param string $text The notification's text
     * @param string $title The notification's title
     *
     * @return bool Returns whether the notification was successfully added or 
     * not.
     */
    public function add($type, $text, $title = null,$options = []) {
        $allowedTypes = ['error', 'info', 'success', 'warning'];
        if(!in_array($type, $allowedTypes)) return false;

        $this->notifications[] = [
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'options' => $options
        ];

        $this->session->flash('flash::alerts', $this->notifications);

    }

    /**
     * Shortcut for adding an info notification
     *
     * @param string $text The notification's text
     * @param string $title The notification's title
     */
    public function info($text, $title = null, $options = []) {
        $this->add('info', $text, $title, $options);
    }

    /**
     * Shortcut for adding an error notification
     *
     * @param string $text The notification's text
     * @param string $title The notification's title
     */
    public function error($text, $title = null, $options = []) {
        $this->add('error', $text, $title, $options);
    }

    /**
     * Shortcut for adding a warning notification
     *
     * @param string $text The notification's text
     * @param string $title The notification's title
     */
    public function warning($text, $title = null, $options = []) {
        $this->add('warning', $text, $title, $options);
    }

    /**
     * Shortcut for adding a success notification
     *
     * @param string $text The notification's text
     * @param string $title The notification's title
     */
    public function success($text, $title = null, $options = []) {
        $this->add('success', $text, $title, $options);
    }

    /**
     * Clear all notifications
     */
    public function clear() {
        $this->notifications = [];
        $this->session->forget('flash::alerts');
    }

    public function get() {
        return $this->session->has('flash::alerts') ? $this->session->get('flash::alerts') : [];
    }



}
