<?php
namespace Vendimia\Html;

use ArrayAccess;

/**
 * HTML tag creation
 *
 * @author Oliver Etchebarne <yo@drmad.org>
 */
class Tag implements ArrayAccess
{
    private $options = [
        /** True to force the close tag, even if there is no content. */
        'closetag' => false,

        /** True if only draws the open tag */
        'onlyopentag' => false,

        // Executes htmlentities() on the content
        'escapecontent' => true,

        // Execute htmlentities() on the attributes values
        'escapeattributes' => true,

    ];

    // true cuando ya fue construido el tag
    private $built = false;

    // El HTML final
    private $html = null;

    // El construct es con todas las opcions
    public function __construct (
        private string $name,
        private array $attributes = [],
        private $content = null,
        array $options = []
    ) {

        $this->options = array_replace($this->options, $options);
    }


    /**
     * Builds and returns the HTML tag.
     */
    private function get()
    {
        // Evitamos reconstruir el mismo tag.
        if ($this->built) {
            return $this->html;
        }

        $tag = '<' . $this->name;

        // Añadimos las variables
        if ($this->attributes) {
            $params = [];

            foreach ($this->attributes as $name => $value) {

                // Si empieza con '@', es una configuración
                if ($name[0] == '@') {

                    $var = substr ($name, 1);

                    // Solo modificamos la configuración si existe
                    if (isset($this->options[$var])) {
                        $this->options [$var] = $value;
                        continue;
                    }
                }

                // Escapamos?
                if ($this->options['escapeattributes']) {
                    $value = addslashes(htmlspecialchars($value));
                }

                $params[] = $name . '="' . $value . '"';
            }

            $tag .= ' ' . join (' ', $params );
        }

        // Hay contenido?
        if (!is_null($this->content)) {
            if ($this->options['escapecontent']) {
                $this->content = htmlspecialchars($this->content);
            }

            $tag .=  '>' . $this->content . '</' . $this->name . '>';
        } else {
            // Solo queremos el opentag?
            if (!$this->options['onlyopentag']) {

                // Si no hay contenido, y si forzamos un closetag, lo ... forzamos
                if ($this->options['closetag']) {
                    $tag .= '></' . $this->name;
                } else {
                    $tag .= ' /';
                }
            }
            $tag .= '>';
        }

        $this->html = $tag;
        $this->built = true;

        return $tag;
    }

    /**
     * Adds content to this tag
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Adds attributes to this tag
     */
    public function addAttributes(...$attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Shorthand for create a tag using static method name.
     */
    public static function __callStatic($name, $attributes)
    {
        $content = null;
        if (isset($attributes[0])) {
            $content = $attributes[0];
            unset($attributes[0]);
        }
        return new self($name, $attributes, $content);
    }

    /**
     * Shorthand for add a tag content.
     */
    public function __invoke($content): self
    {
        $this->setContent($content);

        return $this;
    }

    /**
     * Forces drawing the close tag, even if there is no content.
     */
    public function closeTag(): self
    {
        $this->options['closetag'] = true;

        return $this;
    }

    /**
     * Renders only the open tag
     */
    public function onlyOpenTag(): self
    {
        $this->options['onlyopentag'] = true;

        return $this;
    }

    /**
     * Prevents convert special chars to html entities in content
     */
    public function noEscapeContent(): self
    {
        $this->options['escapecontent'] = false;

        return $this;
    }

    /**
     * Prevents convert special chars to html entities in attributes value
     */
    public function noEscapeAttributes(): self
    {
        $this->options['escapeattributes'] = false;

        return $this;
    }

    /**
     * Returns the HTML tag in a string context
     */
    public function __toString()
    {
        return $this->get();
    }

    // ArrayAccess

    /**
     * Adds an HTML attribute
     */
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Not needed.
     */
    public function offsetGet($offset): mixed {}

    /**
     * Not needed.
     */
    public function offsetExists ($offset): bool {}

    /**
     * Not needed.
     */
    public function offsetUnset ($offset): void {}
}
