<?php
require_once 'models/Entreprise.php';

class EntrepriseController {
    public function listEntreprises() {
        $entreprises = Entreprise::getAll();
        require 'views/layout/header.php';
        require 'views/entreprises.php';
        require 'views/layout/footer.php';
    }

    public function showForm() {
        $message = Entreprise::handleForm();
        require 'views/layout/header.php';
        require 'views/form.php';
        require 'views/layout/footer.php';
    }
}
