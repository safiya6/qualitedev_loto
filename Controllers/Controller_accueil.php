<?php
namespace App\Controller;

class Controller_accueil extends Controller
{
    /**
     * Action par défaut
     */
    public function action_default()
    {
        $this->render("accueil");
    }
}
?>
