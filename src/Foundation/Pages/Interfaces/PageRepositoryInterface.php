<?php
namespace Soda\Cms\Foundation\Pages\Interfaces;

use Illuminate\Http\Request;

interface PageRepositoryInterface
{
    public function findBySlug($slug);
    public function getPageTypes();
    public function getPageTree();
    public function getRoot();
    public function createRoot();
    public function createStub($parentId = null, $pageTypeId = null);
    public function save(Request $request, $id = null);
    public function destroy($id);
}
