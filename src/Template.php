<?php

namespace memclutter\PhpTodo;

class Template
{
    use ContainerTrait;

    /**
     * @param $templateFile
     * @return string
     * @throws Exception
     */
    public function render($templateFile)
    {
        $templateFilePath = implode(DIRECTORY_SEPARATOR, [
            $this->getTemplateDir(),
            Utils::normalizeFilePath($templateFile),
        ]);

        if (!file_exists($templateFilePath)) {
            throw new Exception("Template file '{$templateFilePath}' not found.");
        }

        ob_start();
        extract($this->toArray());
        /** @noinspection PhpIncludeInspection */
        require($templateFilePath);
        return ob_get_clean();
    }

    public function getTemplateDir()
    {
        $config = $this->config();
        if (!isset($config['templateDir'])) {
            throw new Exception('Invalid configuration, missing "templateDir" param.');
        }

        if (!is_dir($config['templateDir'])) {
            throw new Exception("Invalid template dir, not found '{$config['templateDir']}'.");
        }

        if (!is_readable($config['templateDir'])) {
            throw new Exception("Invalid template dir, not readable '{$config['templateDir']}'.");
        }

        return $config['templateDir'];
    }

    /**
     * @return Layout
     */
    public function layout()
    {
        return $this->application()->layout;
    }

    /**
     * @return array
     */
    public function config()
    {
        return $this->application()->config;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return Application::getInstance();
    }
}