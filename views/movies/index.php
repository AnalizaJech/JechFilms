<?php
/**
 * Vista: Catálogo de Películas
 */
$pageTitle = 'Películas';

// Obtener nombre de la categoría seleccionada
$selectedCategoryName = 'Categoría';
if (get('category')) {
    foreach ($categories as $cat) {
        if ($cat['id'] == get('category')) {
            $selectedCategoryName = $cat['name'];
            break;
        }
    }
}

ob_start();
?>

<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-6">
        
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold mb-1">Películas</h1>
                <p class="text-gray-500 text-sm">Explora nuestra colección completa</p>
            </div>
            
            <!-- Filtros -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Categoría -->
                <div class="relative" id="category-dropdown">
                    <button type="button" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2.5 text-sm transition" onclick="toggleDropdown('category')">
                        <span class="<?= get('category') ? 'text-red-400' : 'text-gray-300' ?>"><?= e($selectedCategoryName) ?></span>
                        <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="category-arrow">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="hidden absolute top-full left-0 mt-2 w-52 bg-[#1c1c1c] rounded-xl shadow-2xl border border-white/10 overflow-hidden z-50" id="category-menu">
                        <div class="p-2 max-h-72 overflow-y-auto">
                            <button onclick="setCategory('')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm transition <?= !get('category') ? 'bg-red-500/15 text-red-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                Todas las categorías
                            </button>
                            <?php foreach ($categories as $cat): ?>
                            <button onclick="setCategory('<?= $cat['id'] ?>')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm transition <?= get('category') == $cat['id'] ? 'bg-red-500/15 text-red-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                <?= e($cat['name']) ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Ordenar -->
                <?php $sortLabels = ['recent' => 'Más recientes', 'popular' => 'Más vistas', 'liked' => 'Más gustadas', 'year' => 'Por año']; ?>
                <div class="relative" id="sort-dropdown">
                    <button type="button" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2.5 text-sm transition" onclick="toggleDropdown('sort')">
                        <span><?= $sortLabels[get('sort', 'recent')] ?? 'Más recientes' ?></span>
                        <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="sort-arrow">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="hidden absolute top-full right-0 mt-2 w-44 bg-[#1c1c1c] rounded-xl shadow-2xl border border-white/10 overflow-hidden z-50" id="sort-menu">
                        <div class="p-2">
                            <?php foreach ($sortLabels as $value => $label): ?>
                            <button onclick="setSort('<?= $value ?>')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm transition <?= get('sort', 'recent') === $value ? 'bg-red-500/15 text-red-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                <?= $label ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grid -->
        <?php if (empty($movies)): ?>
            <div class="text-center py-24">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-white/5 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-300 mb-2">No hay películas</h2>
                <p class="text-gray-500 mb-6">Aún no hay películas en esta categoría</p>
                <?php if (isAdmin()): ?>
                <a href="<?= url('admin/movies/create') ?>" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar Película
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <?php foreach ($movies as $movie): ?>
                    <?php include VIEWS_PATH . '/components/movie-card.php'; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Paginación -->
            <?php if (isset($pagination) && $pagination['last_page'] > 1): ?>
            <div class="flex justify-center mt-14">
                <nav class="flex items-center gap-2">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="<?= url('movies?page=' . ($pagination['current_page'] - 1) . '&category=' . get('category') . '&sort=' . get('sort', 'recent')) ?>" 
                           class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition">← Anterior</a>
                    <?php endif; ?>
                    <span class="px-4 py-2 text-sm text-gray-500"><?= $pagination['current_page'] ?> / <?= $pagination['last_page'] ?></span>
                    <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                        <a href="<?= url('movies?page=' . ($pagination['current_page'] + 1) . '&category=' . get('category') . '&sort=' . get('sort', 'recent')) ?>" 
                           class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition">Siguiente →</a>
                    <?php endif; ?>
                </nav>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
    </div>
</div>

<script>
let openDropdown = null;

function toggleDropdown(name) {
    const menu = document.getElementById(name + '-menu');
    const arrow = document.getElementById(name + '-arrow');
    
    if (openDropdown && openDropdown !== name) {
        document.getElementById(openDropdown + '-menu').classList.add('hidden');
        document.getElementById(openDropdown + '-arrow').style.transform = '';
    }
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
        openDropdown = name;
    } else {
        menu.classList.add('hidden');
        arrow.style.transform = '';
        openDropdown = null;
    }
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('#category-dropdown') && !e.target.closest('#sort-dropdown')) {
        ['category', 'sort'].forEach(name => {
            document.getElementById(name + '-menu')?.classList.add('hidden');
            const arrow = document.getElementById(name + '-arrow');
            if (arrow) arrow.style.transform = '';
        });
        openDropdown = null;
    }
});

function setCategory(id) {
    const sort = '<?= get('sort', 'recent') ?>';
    let url = '<?= url('movies') ?>?sort=' + sort;
    if (id) url += '&category=' + id;
    window.location.href = url;
}

function setSort(value) {
    const category = '<?= get('category', '') ?>';
    let url = '<?= url('movies') ?>?sort=' + value;
    if (category) url += '&category=' + category;
    window.location.href = url;
}
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
