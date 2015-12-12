<?php

namespace memclutter\PhpTodo;

/**
 * Class Logger
 * @package memclutter\PhpTodo
 *
 * @method void e(string $category, string $message) write error message.
 * @method void w(string $category, string $message) write warning message.
 * @method void i(string $category, string $message) write info message.
 * @method void d(string $category, string $message) write debug message.
 */
class Logger
{
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_INFO = 'INFO';
    const LEVEL_DEBUG = 'DEBUG';

    private $_targetFile;
    private $_level;
    private $_dateFormat = 'Y-m-d H:i:s';
    private $_lineFormat = "[{date}] - {ip} - {level} - {category} - {message}\n";

    public static function availableLevels()
    {
        return [
            'e' => self::LEVEL_ERROR,
            'w' => self::LEVEL_WARNING,
            'i' => self::LEVEL_INFO,
            'd' => self::LEVEL_DEBUG,
        ];
    }

    public function __construct($targetFile = null, $level = null, $dateFormat = null, $lineFormat = null)
    {
        if ($targetFile == null) {
            $targetFile = implode(DIRECTORY_SEPARATOR, [
                APP_ROOT,
                'log',
                'application.log',
            ]);
        }

        $logDir = dirname($targetFile);
        if (!is_dir($logDir)) {
            throw new Exception("Log dir '{$logDir}' not found.");
        }

        if (!is_writable($logDir)) {
            throw new Exception("Log dir '{$logDir}' not writable.");
        }

        $this->_targetFile = $targetFile;

        if ($level != null) {
            $availableLevels = $this->availableLevels();
            if (!in_array($level, $availableLevels)) {
                throw new Exception("Unknown log level '{$level}'.");
            }
            $this->_level = $level;
        } else {
            $this->_level = self::LEVEL_WARNING;
        }

        if ($dateFormat != null) {
            $this->_dateFormat = $dateFormat;
        }

        if ($lineFormat != null) {
            $this->_logLineFormat = $lineFormat;
        }
    }

    public function __call($name, $arguments)
    {
        $category = isset($arguments[0]) ? $arguments[0] : 'application';
        $message = isset($arguments[1]) ? $arguments[1] : '';
        $availableLevels = $this->availableLevels();
        if (!isset($availableLevels[$name])) {
            throw new Exception("Unknown log level alias '{$name}', for send message '{$message}' in category '{$category}'.");
        }
        $this->write($category, $message, $availableLevels[$name]);
    }

    public function write($category, $message, $level = self::LEVEL_ERROR)
    {
        if ($this->isAllowLevel($level)) {
            if (is_array($message)) {
                if (isset($message[0])) {
                    $pairs = $message;
                    $message = $message[0];
                    unset($message[0]);
                    $message = strtr($message, $pairs);
                } else {
                    $message = implode("\n", $message);
                }
            }

            $date = date("Y-m-d H:i:s");
            $ip = Utils::getClientIp();

            $line = strtr($this->_lineFormat, [
                '{date}' => $date,
                '{ip}' => $ip,
                '{level}' => $level,
                '{category}' => $category,
                '{message}' => $message,
            ]);
            $this->writeToFile($line);
        }
    }

    private function isAllowLevel($level)
    {
        $allowLevels = [];
        switch ($this->_level) {
            case self::LEVEL_DEBUG:
                $allowLevels = [
                    self::LEVEL_ERROR,
                    self::LEVEL_WARNING,
                    self::LEVEL_INFO,
                    self::LEVEL_DEBUG,
                ];
                break;
            case self::LEVEL_INFO:
                $allowLevels = [
                    self::LEVEL_ERROR,
                    self::LEVEL_WARNING,
                    self::LEVEL_INFO,
                ];
                break;
            case self::LEVEL_WARNING:
                $allowLevels = [
                    self::LEVEL_ERROR,
                    self::LEVEL_WARNING,
                ];
                break;
            case self::LEVEL_ERROR:
                $allowLevels = [
                    self::LEVEL_ERROR,
                ];
                break;
        }
        return in_array($level, $allowLevels);
    }

    private function writeToFile($line)
    {
        error_log($line, 3, $this->_targetFile);
    }
}