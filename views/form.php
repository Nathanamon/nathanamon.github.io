<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $file = $_FILES['resume'];

        if ($file['size'] > 2 * 1024 * 1024) {
            $message = "Fichier trop volumineux.";
        } else {
            $allowed = [
                'application/pdf',
                'application/x-pdf',
                'application/octet-stream',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            $mime = finfo_file($finfo, $file['tmp_name']);

            if (in_array($mime, $allowed)) {
                $filename = "uploads/" . basename($file['name']);
                move_uploaded_file($file['tmp_name'], $filename);
                $message = "Candidature envoyée avec succès.";
            } else {
                $message = "Type de fichier non autorisé.";
            }
        }
    } else {
        $message = "Erreur lors de l'envoi.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>


<h1 style="text-align: center;">Formulaire de candidature</h1>

<?php if ($message): ?>
    <p style="text-align: center; color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="fullName">Nom Complet</label>
    <input type="text" id="fullName" name="fullName" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="phone">Téléphone:</label>
    <input type="tel" id="phone" name="phone" required>

    <label for="position">Titre de l'offre:</label>
    <input type="text" id="position" name="position" value="<?= isset($_GET['poste']) ? htmlspecialchars($_GET['poste']) : '' ?>" required>

    <label for="resume">CV:</label>
    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>

    <label for="coverLetter">Lettre de motivation:</label>
    <textarea id="coverLetter" name="coverLetter" rows="5" required></textarea>

    <button type="submit">Soumettre ma candidature</button>
</form>

<div style="text-align: center; margin-bottom: 20px;">
    <a href="entreprises.php" class="button">Retour aux annonces</a>
</div>

</body>
</html>
