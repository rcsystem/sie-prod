<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Mis Insignias
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .badge-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 1rem;
    overflow: hidden;
    background: #fff;
    position: relative;
  }

  .badge-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  }

  .badge-image {
    max-height: 150px;
    object-fit: contain;
    margin: auto;
    display: block;
  }

  .group-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin: 2rem 0 1rem;
    position: relative;
    padding-left: 15px;
  }

  .group-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 5px;
    height: 60%;
    background: linear-gradient(to bottom, #007bff, #00bfff);
    border-radius: 5px;
  }

  .counter-box {
    background: linear-gradient(135deg, #f4f6f9, #e9ecef);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease;
  }

  .counter-box:hover {
    transform: scale(1.05);
  }

  .filter-section {
    margin-bottom: 2rem;
  }

  .year-divider {
    margin: 2rem 0;
    text-align: center;
  }

  .year-divider span {
    font-size: 1.2rem;
    color: #555;
  }

  .category-badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin-top: 0.5rem;
  }
</style>

<?php
// Lista de insignias (mismo array que antes)
// Lista de insignias


// Contadores por categoría
$categorias = [
  'Voluntariado' => 0,
  'Acciones Verdes' => 0,
  'Actividades Deportivas' => 0,
];

$agrupadasPorAnio = [];

// Agrupar por año y contar
foreach ($badges as $badge) {
  $anio = date('Y', strtotime($badge['fecha']));
  $agrupadasPorAnio[$anio][] = $badge;

  if (isset($categorias[$badge['categoria']])) {
    $categorias[$badge['categoria']]++;
  }
}

// Ordenar los años de forma descendente (primero el más reciente)
krsort($agrupadasPorAnio);
?>

<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Título -->
    <div class="row mb-4">
      <div class="col-12 text-center">
        <h1 class="display-4"><i class="fas fa-award mr-2"></i>Mis Insignias</h1>
        <p class="text-muted">Consulta tus logros organizados por año y categoría</p>
      </div>
    </div>

    <!-- Contadores -->
    <div class="row justify-content-center filter-section">
      <?php foreach ($categorias as $nombre => $total): ?>
        <div class="col-md-3 col-sm-6" style="cursor:pointer;">
          <div class="counter-box shadow-sm">
            <h5 class=""><?= ucfirst($nombre) ?></h5>
            <span class="badge badge-pill badge-dark" style="font-size: 1.3rem;"><?= $total ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Filtro -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-4">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-light"><i class="fas fa-filter"></i></span>
          </div>
          <select class="form-control" id="filter-category">
            <option value="all">Todas las categorías</option>
            <option value="Voluntariado">Voluntariado</option>
            <option value="Acciones Verdes">Acciones Verdes</option>
            <option value="Actividades Deportivas">Actividades Deportivas</option>

          </select>
        </div>
      </div>
    </div>

    <!-- Insignias agrupadas por año -->
    <?php foreach ($agrupadasPorAnio as $anio => $lista): ?>
      <div class="year-divider">
        <h1><?= $anio ?></h1>
      </div>
      <div class="row" id="badges-container">
        <?php foreach ($lista as $badge): ?>
          <div class="col-lg-3 col-md-4 col-sm-3 mb-3" data-category="<?= $badge['categoria'] ?>" style="cursor:pointer;">
            <div class="card badge-card shadow">
              <div class="card-header bg-<?= $badge['color'] ?> text-white text-center">
                <h5 class="card-title mb-0" style="font-size:16px;"><?= $badge['evento'] ?></h5>
              </div>
              <div class="card-body text-center">
                <img src="<?= base_url() ?><?= $badge['imagen'] ?>" alt="Insignia" class="img-fluid badge-image mb-3">
                <span class="badge category-badge bg-<?= $badge['color'] ?>"><?= ucfirst($badge['categoria']) ?></span>
              </div>
              <div class="card-footer text-muted text-center">
                Obtenido: <?= $badge['fecha'] ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function () {
  $('#filter-category').change(function () {
    const selected = $(this).val();

    $('[data-category]').each(function () {
      const cat = $(this).data('category');

      if (selected === 'all' || selected === cat) {
        $(this).fadeIn();  // o .show()
      } else {
        $(this).fadeOut(); // o .hide()
      }
    });
  });
});

</script>
<?= $this->endSection() ?>