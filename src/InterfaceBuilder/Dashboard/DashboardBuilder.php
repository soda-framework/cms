<?php

namespace Soda\Cms\InterfaceBuilder\Dashboard;

use Illuminate\Contracts\Support\Renderable;

class DashboardBuilder implements Renderable
{
    protected $renderingEvents = [
        'dashboard' => [],
        'row'       => [],
        'block'     => [],
    ];

    protected $blocks = [];
    protected $rows = [];

    public function rendering(callable $callback)
    {
        $this->renderingEvents['dashboard'][] = $callback;

        return $this;
    }

    public function renderingRow($rowId, callable $callback)
    {
        $this->renderingEvents['row'][$rowId][] = $callback;

        return $this;
    }

    public function renderingBlock($blockName, callable $callback)
    {
        $this->renderingEvents['block'][$blockName][] = $callback;

        return $this;
    }

    public function addBlockToRow($rowId, $blockName, callable $callback)
    {
        $this->blocks[$blockName] = $callback;

        if (! isset($this->rows[$rowId])) {
            $this->rows[$rowId] = [];
        }

        $this->rows[$rowId][] = $blockName;

        return $this;
    }

    public function addBlock($blockName, callable $callback)
    {
        $this->blocks[$blockName] = $callback;

        return $this;
    }

    public function removeBlock($blockName)
    {
        if (isset($this->blocks[$blockName])) {
            unset($this->blocks[$blockName]);

            foreach ($this->rows as $rowId => $row) {
                $this->removeBlockFromRow($rowId, $blockName);
            }
        }

        return $this;
    }

    public function removeBlockFromRow($rowId, $blockName)
    {
        if (isset($this->rows[$rowId])) {
            $blockKey = array_search($blockName, $this->rows[$rowId]);

            if ($blockKey !== false) {
                unset($this->rows[$rowId][$blockKey]);
            }
        }

        return $this;
    }

    public function render()
    {
        $this->beforeRender();

        $this->rows = array_filter($this->rows);

        $rows = [];

        if (! count($this->rows)) {
            $this->rows = [array_keys($this->blocks)];
        }

        foreach ($this->rows as $rowId => $blockNames) {
            if ($rowHtml = $this->renderRow($rowId)) {
                $rows[] = $rowHtml;
            }
        }

        return soda_cms_view('partials.dashboard.layout', compact('rows'))->render();
    }

    protected function beforeRender()
    {
        foreach ($this->renderingEvents['dashboard'] as $event) {
            $event($this);
        }
    }

    protected function renderRow($rowId)
    {
        $this->beforeRenderRow($rowId);

        $blockNames = $this->rows[$rowId];
        $blocks = [];

        foreach ($blockNames as $blockName) {
            if ($blockHtml = $this->renderBlock($blockName)) {
                $blocks[] = $blockHtml;
            }
        }

        if (count($blocks)) {
            return soda_cms_view('partials.dashboard.row', compact('blocks'))->render();
        }

        return;
    }

    protected function beforeRenderRow($rowId)
    {
        if (isset($this->renderingEvents['row'][$rowId])) {
            foreach ($this->renderingEvents['row'][$rowId] as $event) {
                $event($this, $this->rows[$rowId]);
            }
        }
    }

    protected function renderBlock($blockName)
    {
        $this->beforeRenderBlock($blockName);

        if (isset($this->blocks[$blockName])) {
            $content = $this->blocks[$blockName]();

            if (is_string($content)) {
                return $content;
            } elseif ($content instanceof Renderable) {
                return $content->render();
            }
        }

        return;
    }

    protected function beforeRenderBlock($blockName)
    {
        if (isset($this->renderingEvents['block'][$blockName])) {
            foreach ($this->renderingEvents['block'][$blockName] as $event) {
                $event($this, $this->blocks[$blockName]);
            }
        }
    }
}
