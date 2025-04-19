<?php
require_once 'Entreprise.php';

// Récupérer le numéro de page (par défaut: 1)
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Récupérer les offres et les infos de pagination
$offres = Entreprise::getAll($page);
$pagination = Entreprise::getPagination($page);
?>

<section class="job-listings">
    <ul>
        <?php foreach ($offres as $offre): ?>
            <li>
                <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                <p>Entreprise : <?= htmlspecialchars($offre['entreprise']) ?></p>
                <p>Localisation : <?= htmlspecialchars($offre['lieu']) ?></p>
                <p>Description : <?= htmlspecialchars($offre['description']) ?></p>
                <p>Salaire : <?= htmlspecialchars($offre['salaire']) ?></p>
                <p>Type de contrat : <?= htmlspecialchars($offre['type_contrat']) ?></p>
                
                <!-- Lien de postulation avec l'ID de l'offre -->
                <a href="index.php?page=form&id=<?= $offre['id'] ?>&poste=<?= urlencode($offre['titre']) ?>">
                    Postuler
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($pagination['has_previous']): ?>
            <a href="?page=<?= $pagination['current'] - 1 ?>">Précédent</a>
        <?php endif; ?>
        
        <span>Page <?= $pagination['current'] ?> sur <?= $pagination['total'] ?></span>
        
        <?php if ($pagination['has_next']): ?>
            <a href="?page=<?= $pagination['current'] + 1 ?>">Suivant</a>
        <?php endif; ?>
    </div>
</section>