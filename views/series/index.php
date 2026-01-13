<?php
/**
 * Vista: Catálogo de Series - Premium
 */
$pageTitle = 'Series';

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

$sortLabels = ['recent' => 'Más recientes', 'popular' => 'Más vistas', 'liked' => 'Más gustadas'];

ob_start();
?>

<div class="pt-24 pb-12 min-h-screen">
    <div class="container mx-auto px-6">
        
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold mb-1">Series</h1>
                <p class="text-gray-500 text-sm">Descubre series completas</p>
            </div>
            
            <!-- Filtros -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Categoría -->
                <div class="relative" id="category-dropdown">
                    <button type="button" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2.5 text-sm transition" onclick="toggleDropdown('category')">
                        <span class="<?= get('category') ? 'text-purple-400' : 'text-gray-300' ?>"><?= e($selectedCategoryName) ?></span>
                        <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="category-arrow">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="hidden absolute top-full left-0 mt-2 w-52 bg-[#1c1c1c] rounded-xl shadow-2xl border border-white/10 overflow-hidden z-50" id="category-menu">
                        <div class="p-2 max-h-72 overflow-y-auto">
                            <button onclick="setCategory('')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm transition <?= !get('category') ? 'bg-purple-500/15 text-purple-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                Todas las categorías
                            </button>
                            <?php foreach ($categories as $cat): ?>
                            <button onclick="setCategory('<?= $cat['id'] ?>')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm transition <?= get('category') == $cat['id'] ? 'bg-purple-500/15 text-purple-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                <?= e($cat['name']) ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Ordenar -->
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
                            <button onclick="setSort('<?= $value ?>')" class="w-full text-left px-3 py-2.5 rounded-lg text-sm transition <?= get('sort', 'recent') === $value ? 'bg-purple-500/15 text-purple-400' : 'text-gray-300 hover:bg-white/5' ?>">
                                <?= $label ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grid -->
        <?php if (empty($series)): ?>
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-800/50 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-300 mb-2">No hay series</h2>
                <p class="text-gray-500 mb-6">Aún no hay series disponibles</p>
                <?php if (isAdmin()): ?>
                <a href="<?= url('admin/series/create') ?>" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar Serie
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <?php foreach ($series as $item): ?>
                    <?php include VIEWS_PATH . '/components/series-card.php'; ?>
                <?php endforeach; ?>
            </div>
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
    let url = '<?= url('series') ?>?sort=' + sort;
    if (id) url += '&category=' + id;
    window.location.href = url;
}

function setSort(value) {
    const category = '<?= get('category', '') ?>';
    let url = '<?= url('series') ?>?sort=' + value;
    if (category) url += '&category=' + category;
    window.location.href = url;
}
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/main.php';
?>
