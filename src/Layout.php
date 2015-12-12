<?php

namespace memclutter\PhpTodo;

class Layout
{
    use ContainerTrait;

    const POS_HEAD_BEGIN = 'head begin';
    const POS_HEAD_END = 'head end';
    const POS_BODY_BEGIN = 'body begin';
    const POS_BODY_END = 'body end';

    public $content;
    public $name;
    private $_layoutDir;

    private $_title;
    private $_charset;
    private $_css = [];
    private $_js = [];

    public function __construct($layoutDir)
    {
        $this->_layoutDir = $layoutDir;
    }

    public function render()
    {
        $layoutFilePath = implode(DIRECTORY_SEPARATOR, [
            $this->_layoutDir,
            Utils::normalizeFilePath($this->name),
        ]);

        if (!file_exists($layoutFilePath)) {
            throw new Exception("Layout file '{$layoutFilePath}' not found.");
        }

        Application::getInstance()
            ->logger
            ->d('LAYOUT', ['Render layout file {layout}.', '{layout}' => $layoutFilePath]);

        ob_start();
        extract($this->toArray());
        /** @noinspection PhpIncludeInspection */
        require($layoutFilePath);
        return ob_get_clean();
    }

    public function title($data = null)
    {
        if ($data !== null) {
            $this->_title = $data;
        }
        return $this->_title;
    }

    public function charset($data = null)
    {
        if ($data !== null) {
            $this->_charset = $data;
        }
        return $this->_charset;
    }

    public function addCss($data = null, $pos = self::POS_HEAD_END)
    {
        $this->_css[$pos][] = $data;
    }

    public function addJs($data = null, $pos = self::POS_HEAD_END)
    {
        $this->_js[$pos][] = $data;
    }

    public function head()
    {
        if (isset($this->_css[self::POS_HEAD_BEGIN])) {
            $this->renderCss($this->_css[self::POS_HEAD_BEGIN]);
        }

        if (isset($this->_js[self::POS_HEAD_BEGIN])) {
            $this->renderJs($this->_js[self::POS_HEAD_BEGIN]);
        }

        if (isset($this->_css[self::POS_HEAD_END])) {
            $this->renderCss($this->_css[self::POS_HEAD_END]);
        }

        if (isset($this->_js[self::POS_HEAD_END])) {
            $this->renderJs($this->_js[self::POS_HEAD_END]);
        }
    }

    public function beginBody()
    {
        if (isset($this->_css[self::POS_BODY_BEGIN])) {
            $this->renderCss($this->_css[self::POS_BODY_BEGIN]);
        }

        if (isset($this->_js[self::POS_BODY_BEGIN])) {
            $this->renderJs($this->_js[self::POS_BODY_BEGIN]);
        }
    }

    public function endBody()
    {
        if (isset($this->_css[self::POS_BODY_END])) {
            $this->renderCss($this->_css[self::POS_BODY_END]);
        }

        if (isset($this->_js[self::POS_BODY_END])) {
            $this->renderJs($this->_js[self::POS_BODY_END]);
        }
    }

    private function renderCss($css)
    {
        foreach ($css as $data) {
            if (is_array($data)) {
                $attributes = [];
                foreach ($data as $name => $value) {
                    $attributes[] = "$name=\"$value\"";
                }
                echo '<link ' . implode(' ', $attributes) . '>';
            } else {
                echo $data;
            }
        }
    }

    private function renderJs($js)
    {
        foreach ($js as $data) {
            if (is_array($data)) {
                $attributes = [];
                $script = null;
                foreach ($data as $name => $value) {
                    if (is_int($name)) {
                        $script = $value;
                    } else {
                        $attributes[] = "$name=\"$value\"";
                    }
                }
                echo '<script ' . implode(' ', $attributes) . '>' . $script . '</script>';
            } else {
                echo $data;
            }
        }
    }
}