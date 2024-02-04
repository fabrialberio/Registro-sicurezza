<script>
    function showSidebar() {
        body = document.getElementsByTagName('body')[0]
        body.classList.add('sidebar-open');
        body.classList.remove('sidebar-collapse');
    }
    
    function hideSidebar() {
        body = document.getElementsByTagName('body')[0]
        body.classList.remove('sidebar-open');
        body.classList.add('sidebar-collapse');
    }
</script>

<style>
    @media screen and (min-width: 991px) {
        .show-only-on-mobile {
            display: none !important;
        }
    }

    @media screen and (max-width: 991px) {
        .show-only-on-desktop {
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
        <div class="">
            <a class="btn btn-flat-light mt-2 show-only-on-mobile" onclick="hideSidebar();">
                <i class="fas fa-close"></i>
            </a>
        </div>
        <div class="user-panel mt-3 pb-2 mb-2">
            <div class="info text-light">
                <?php 
                    echo get_docente($id_docente)['cognome_nome'];
                ?>
                <?php if ($amministratore): ?>
                <p class="mb-0 text-warning">
                    <small>Amministratore</small>
                </p>
                <?php endif; ?>
            </div>
            <div class="">
                <a class="btn btn-flat-light float-left mr-2" href="dati_docente.php?profile">
                    <i class="fas fa-user"></i>
                    <span class="ml-1">Profilo</span>
                </a>
                <a class="btn btn-flat-light float-left" href="../src/navigation.php?logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="ml-1">Esci</span>
                </a>
            </div>
        </div>
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li>
                    <a class="nav-link btn btn-primary mb-2 text-white" href="nuova_lezione.php">
                        <i class="nav-icon fas fa-add"></i>
                        <p>Nuova lezione</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lezioni.php">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>Visualizza lezioni</p>
                    </a>
                </li>
                <?php if ($amministratore): ?>
                <li class="nav-item">
                    <a class="nav-link" href="studenti.php">
                        <i class="nav-icon fas fa-user-group"></i>
                        <p>Studenti</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="docenti.php">
                        <i class="nav-icon fas fa-chalkboard-user"></i>
                        <p>Docenti</p>
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