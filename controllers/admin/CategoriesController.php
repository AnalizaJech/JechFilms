<?php
/**
 * Controlador de Categorías (Admin)
 */

require_once BASE_PATH . '/models/Category.php';

class CategoriesController {
    private Category $categoryModel;
    
    public function __construct() {
        requireAdmin();
        $this->categoryModel = new Category();
    }
    
    public function index(): void {
        $categories = $this->categoryModel->getAllWithCounts();
        require_once VIEWS_PATH . '/admin/categories/index.php';
    }
    
    public function store(): void {
        if (!isPost() || !validateCsrf()) redirect('admin/categories');
        
        $name = trim(post('name', ''));
        $parentId = post('parent_id') ?: null;
        
        if (empty($name)) {
            flash('error', 'El nombre es requerido.');
            redirect('admin/categories');
        }
        
        $this->categoryModel->createWithSlug($name, $parentId);
        flash('success', 'Categoría creada.');
        redirect('admin/categories');
    }
    
    public function update(int $id = 0): void {
        if (!isPost() || !validateCsrf()) redirect('admin/categories');
        
        $name = trim(post('name', ''));
        if (empty($name)) {
            flash('error', 'El nombre es requerido.');
            redirect('admin/categories');
        }
        
        $this->categoryModel->update($id, [
            'name' => $name,
            'slug' => slugify($name)
        ]);
        
        flash('success', 'Categoría actualizada.');
        redirect('admin/categories');
    }
    
    public function delete(int $id = 0): void {
        $this->categoryModel->delete($id);
        flash('success', 'Categoría eliminada.');
        redirect('admin/categories');
    }
}
