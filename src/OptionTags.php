<?php
namespace Vendimia\Html;

/**
 * Renders a set of <OPTION> tags
 *
 * @author Oliver Etchebarne <yo@drmad.org>
 */
class OptionTags
{
    public function __construct(
        private array $options,
        private array $select_list = [],
        private array $disable_list = [],
    )
    {
    }

    /**
     * Renders the tags list
     */
    public function render(): string
    {
        $html = '';

        // Las listas no tienen el atributo 'value'
        $is_list = array_is_list($this->options);

        foreach ($this->options as $id => $value) {
            if (is_array ($value)) {
                $html .= Tag::optgroup(label: $id)
                    ->setContent(new self($value, $this->select_list, $this->disable_list))
                    ->noEscapeContent()
                ;
            } else {
                $vars = [];

                if (!$is_list) {
                    $vars['value'] =  $id;
                }

                if ($this->select_list && in_array($id, $this->select_list)) {
                    $vars['selected'] = 'true';
                }
                if ($this->disable_list && in_array($id, $this->disable_list)) {
                    $vars['disabled'] = 'true';
                }


                $html .= Tag::option(...$vars)
                    ->setContent($value)
                    ->noEscapeContent()
                ;
            }
         }
         return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
