<?php
class Entreprise {
    private static $db;

    // Initialisation de la connexion à la base de données
    public static function initDB() {
        try {
            self::$db = new PDO(
                'mysql:host=mysql-lebonplan.alwaysdata.net;dbname=lebonplan_db;charset=utf8',
                'lebonplan',
                'Kouadiorebecca@',
            );
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    // Récupère les offres avec pagination
    public static function getAll($page = 1, $perPage = 10) {
        self::initDB();
        
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM offres_emploi LIMIT :limit OFFSET :offset";
        
        try {
            $stmt = self::$db->prepare($query);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des offres: " . $e->getMessage());
        }
    }

    // Compte le nombre total d'offres
    public static function countOffres() {
        self::initDB();
        
        try {
            $stmt = self::$db->query("SELECT COUNT(*) FROM offres_emploi");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            die("Erreur lors du comptage des offres: " . $e->getMessage());
        }
    }

    // Génére la pagination
    public static function getPagination($currentPage, $perPage = 10) {
        $totalOffres = self::countOffres();
        $totalPages = ceil($totalOffres / $perPage);
        
        $pagination = [
            'current' => $currentPage,
            'total' => $totalPages,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages
        ];
        
        return $pagination;
    }

    public static function handleForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['resume']) {
                // Créer le dossier uploads s'il n'existe pas
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                $file = $_FILES['resume'];
                if ($file['error'] !== 0) return "Erreur lors de l'envoi du fichier.";

                // Vérification de la taille (2Mo max)
                if ($file['size'] > 2 * 1024 * 1024) return "Fichier trop volumineux (max 2Mo).";

                // Vérification du type MIME
                $allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                
                if (!in_array($mime, $allowedTypes)) {
                    return "Type de fichier non autorisé (PDF ou DOC/DOCX uniquement).";
                }

                // Générer un nom de fichier unique
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('cv_', true) . '.' . $extension;
                
                if (move_uploaded_file($file['tmp_name'], 'uploads/' . $filename)) {
                    return "Candidature envoyée avec succès.";
                } else {
                    return "Erreur lors de l'enregistrement du fichier.";
                }
            }
            return "Aucun fichier n'a été envoyé.";
        }
        return "";
    }
}