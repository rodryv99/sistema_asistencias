<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Sistema de Control de Asistencias</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-brand">
                    <h2><i class="fas fa-graduation-cap"></i> Control de Asistencias</h2>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/periodos" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/periodos') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-calendar-alt"></i> Períodos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/materias" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/materias') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-book"></i> Materias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/estudiantes" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/estudiantes') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-users"></i> Estudiantes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/grupos" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/grupos') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-layer-group"></i> Grupos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/asistencias" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/asistencias') !== false || strpos($_SERVER['REQUEST_URI'], '/asistencias/crear') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-check-circle"></i> Asistencias
                        </a>
                    </li>
                </ul>
                
                <div class="nav-toggle" id="mobile-menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <div class="container">
                <?php // CÓDIGO CORREGIDO PARA MOSTRAR MENSAJES FLASH ?>
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" id="flash-message">
                        <i class="fas fa-<?= $flash['type'] == 'success' ? 'check-circle' : ($flash['type'] == 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
                        <?= htmlspecialchars($flash['message']) ?>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
                    </div>
                <?php endif; ?>