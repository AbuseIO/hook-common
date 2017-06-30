<?php
namespace AbuseIO\Hook;

use Singleton;

/**
 * Class HookRegistry
 *
 * Singleton Registry which, keeps hold of the installed hooks
 *
 * @package AbuseIO\Hook
 */
class HookRegistry extends Singleton
{
    private $registry = [];

    public function register($hook)
    {
        if (!in_array($hook, $this->registry)) {
            $this->registry[] = $hook;
        }
    }

    public function deregister($hook)
    {
        if (in_array($hook, $this->registry)) {
            $key = array_search($hook, $this->registry);
            if ($key !== false) {
                unset($this->registry[$key]);
            }
        }
    }

    public function isRegistered($hook)
    {
        return in_array($hook, $this->registry);
    }

    public function __get($variable)
    {
        if (isset($this->$variable)) {
            return $this->$variable;
        }
    }
}