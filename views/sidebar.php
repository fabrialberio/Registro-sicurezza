<?php
include_once '../database/interface.php';

$amministratore = is_amministratore_by_username($token['username']);
?>
<script>
    function showSidebar() {
        document.getElementsByClassName('sidebar-mini')[0].classList.add('sidebar-open');
    }
</script>

<style>
    @media screen and (min-width: 768px) {
        .show-only-on-mobile {
            display: none !important;
        }
    }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light show-only-on-mobile">
    <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" onclick="showSidebar();">
        <i class="fas fa-bars"></i>
        </a>
    </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-2 mb-2">
            <div class="info">
                <a href="../src/navigation.php?action=logout">
                    <?php 
                        echo get_docente(get_id_docente_by_username($token['username']))['cognome_nome'];
                    ?>
                    <i class="nav-icon fas fa-right-to-bracket float-right m-1 mr-3 ml-2"></i>
                    <?php if ($amministratore): ?>
                        <p class="mb-0 text-warning">
                            <small>Amministratore</small>
                        </p>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li>
                    <a class="nav-link btn btn-primary mb-2 text-white" href="home_page.php">
                        <i class="nav-icon fas fa-add"></i>
                        <p>Nuova lezione</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_lessons.php">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>Visualizza lezioni</p>
                    </a>
                </li>
                <?php if ($amministratore): ?>
                <!-- Cambiare icone -->
                <li class="nav-item">
                    <a class="nav-link" href="docenti.php">
                        <i class="nav-icon fas fa-chalkboard-user"></i>
                        <p>Docenti</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="studenti.php">
                        <i class="nav-icon fas fa-user-group"></i>
                        <p>Studenti</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="classi.php">
                        <i class="nav-icon fas fa-chalkboard"></i>
                        <p>Classi</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>