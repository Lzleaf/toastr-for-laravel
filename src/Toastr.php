<?php
/**
 * Created by PhpStorm.
 * User: leaf
 * Date: 2019/9/3
 * Time: 10:38 AM
 */

namespace Leaf\LaraFlash;


use Illuminate\Config\Repository;
use Illuminate\Session\SessionManager;

class Toastr
{
    protected $session;

    protected $config;

    protected $notifications = [];

    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    public function render()
    {
        $notifications = $this->session->get('toastr:notifications');

        if (!$notifications) {
            return '';
        }

        foreach ($notifications as $notification) {
            $config = $this->config->get('toastr.options');
            $javascript = '';
            $options = [];
            if ($config) {
                $options = array_merge($config, $notification['options']);
            }

            if ($options) {
                $javascript = 'toastr.options = ' . json_encode($options) . ';';
            }

            $message = str_replace("'", "\\'", $notification['message']);
            $title = $notification['title'] ? str_replace("'", "\\'", $notification['title']) : null;
            $javascript .= " toastr.{$notification['type']}('$message', '$title');";
        }

        $this->session->forget('toastr:notifications');
        return view('Toastr::toastr', compact('javascript'));
    }

    public function add($type, $message, $title = null, $options = [])
    {
        $types = ['info', 'warning', 'success', 'error'];
        if (!in_array($type, $types)) {
            return false;
        }

        $this->notifications[] = [
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'options' => $options
        ];
        $this->session->flash('toastr:notifications', $this->notifications);
    }

    public function info($message, $title = null, $options = [])
    {
        $this->add('info', $message, $title, $options);
    }

    /**
     * Add warning notification
     * @param $message
     * @param null $title
     * @param array $options
     */
    public function warning($message, $title = null, $options = [])
    {
        $this->add('warning', $message, $title, $options);
    }

    /**
     * Add success notification
     * @param $message
     * @param null $title
     * @param array $options
     */
    public function success($message, $title = null, $options = [])
    {
        $this->add('success', $message, $title, $options);
    }

    /**
     * Add error notification
     * @param $message
     * @param null $title
     * @param array $options
     */
    public function error($message, $title = null, $options = [])
    {
        $this->add('error', $message, $title, $options);
    }

    /**
     * Clear notifications
     */
    public function clear()
    {
        $this->notifications = [];
    }
}