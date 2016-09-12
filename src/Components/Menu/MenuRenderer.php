<?php

namespace Soda\Cms\Components\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Renderer\RendererInterface;

class MenuRenderer extends ListRenderer implements RendererInterface {
    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        if($item->hasChildren() && !$item->isRoot()) {
            $attributes['class'] = 'collapse';
            $attributes['id'] = $item->getCollapsibleId(false);
        }

        return parent::renderList($item, $attributes, $options);
    }

    protected function renderLinkElement(ItemInterface $item, array $options)
    {
        $link_attributes = $item->getLinkAttributes();
        $link_attributes['class'] = isset($link_attributes['class']) ? $link_attributes['class'] . ' nav-link' : 'nav-link';

        return sprintf('<a href="%s"%s%s>%s</a>', $this->escape($item->getUri()), $this->renderHtmlAttributes($link_attributes), $this->renderHtmlAttributes($item->renderCollapseAttributes()), $this->renderLabel($item, $options));
    }

    protected function renderSpanElement(ItemInterface $item, array $options)
    {
        $label_attributes = $item->getLabelAttributes();
        $label_attributes['class'] = isset($label_attributes['class']) ? $label_attributes['class'] . ' nav-link' : 'nav-link';

        return sprintf('<span%s%s>%s</span>', $this->renderHtmlAttributes($label_attributes), $this->renderHtmlAttributes($item->renderCollapseAttributes()), $this->renderLabel($item, $options));
    }

    protected function renderLabel(ItemInterface $item, array $options)
    {
        $label = '<span>' . parent::renderLabel($item, $options) . '</span>';

        if($item->hasIcon()) {
            $label = '<i class="' . $item->getIcon() . '"></i> ' . $label;
        }

        return $label;
    }
}
