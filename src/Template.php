<?php


namespace Futape\SimpleTemplate;


class Template
{
    /** @var array */
    const DEFAULTS = [
        'unescapePlaceholders' => true,
        'resolveConstants' => true,
        'discardUndefinedVariables' => true
    ];

    /** @var array */
    protected $config;

    /** @var string[] */
    protected $variables = [];

    /** @var string */
    protected $template;

    /**
     * @param string $template
     * @param array $config
     */
    public function __construct(string $template, array $config = [])
    {
        $this->setTemplate($template);
        $this->setConfig($config);
    }

    /**
     * @param string|null $option
     * @return array|mixed|null
     */
    public function getConfig(?string $option = null)
    {
        if ($option !== null) {
            return $this->config[$option] ?? null;
        }

        return $this->config;
    }

    /**
     * @param array $config
     * @return self
     */
    public function setConfig(array $config): self
    {
        $this->config = array_merge(self::DEFAULTS, $config);

        return $this;
    }

    /**
     * @param string|null $name
     * @return array|string|null
     */
    public function getVariables(?string $name = null)
    {
        if ($name !== null) {
            return $this->variables[$name] ?? null;
        }

        return $this->variables;
    }

    /**
     * @param array $variables
     * @return self
     */
    public function setVariables(array $variables): self
    {
        foreach (array_keys($this->variables) as $name) {
            $this->removeVariable($name);
        }

        foreach ($variables as $name => $value) {
            $this->addVariable($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param $value
     * @return self
     */
    public function addVariable(string $name, $value): self
    {
        $this->variables[$name] = (string)$value;

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function removeVariable(string $name): self
    {
        unset($this->variables[$name]);

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return preg_replace_callback(
            '/(\{)(\\\\?)(\{\s*(\$?)((?:(?!\}{2})\S)*)\s*\}{2})/',
            function ($matches) {
                if ($matches[2] != '') {
                    if ($this->getConfig('unescapePlaceholders')) {
                        return $matches[1] . $matches[3];
                    } else {
                        return $matches[1] . $matches[2] . $matches[3];
                    }
                }

                if ($matches[4] == ''){
                    if ($this->getConfig('resolveConstants') && defined($matches[5])) {
                        return (string)constant($matches[5]);
                    }
                } else {
                    if ($this->getVariables($matches[5]) !== null) {
                        return $this->getVariables($matches[5]);
                    }
                }

                if ($this->getConfig('discardUndefinedVariables')) {
                    return '';
                } else {
                    return $matches[0];
                }
            },
            $this->getTemplate()
        );
    }
}
