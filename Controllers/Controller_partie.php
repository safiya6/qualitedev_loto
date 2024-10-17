<?php
namespace App\Controllers;

session_start();
use App\Models\Model;

class Controller_partie extends Controller
{
    public function action_default()
    {
        $model = Model::getModel();

        // Récupérer le nombre de joueurs en cours
        $nbJoueursEnCours = $model->countJoueursEnCours();
        $joueurs_creer = $model->selectAllJoueurs_creer();

        // Passer le nombre de joueurs en cours et la liste des joueurs créés à la vue
        $this->render("simulation", [
            'joueurs' => $model->selectJoueursEnCoursPred(),
            'joueurs_creer' => $joueurs_creer,
            'nbJoueursEnCours' => $nbJoueursEnCours,
            'maxSelectable' => max(0, 100 - $nbJoueursEnCours) // Limite dynamique
        ]);
    }

    public function action_addSelectedJoueursCreer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_joueurs'])) {
            $model = Model::getModel();

            // Obtenir les joueurs sélectionnés
            $selectedIds = $_POST['selected_joueurs'];
            $nbJoueursEnCours = $model->countJoueursEnCours();
            $nbToAdd = count($selectedIds);

            // Vérifier la limite de 100 joueurs en cours
            if ($nbJoueursEnCours + $nbToAdd > 100) {
                $_SESSION['message_err'] = "Vous ne pouvez pas ajouter plus de joueurs. Maximum de 100 atteint.";
            } else {
                foreach ($selectedIds as $id_joueur_creer) {
                    // Insérer le joueur dans `joueurs_en_cours`
                    $model->insertJoueurEnCoursFromCreer($id_joueur_creer);
                }
                $_SESSION['message'] = "$nbToAdd joueurs ont été ajoutés à la partie en cours.";
            }
        }

        header("Location: ?controller=partie");
        exit();
    }

    public function action_selectRandomJoueurs() {
        $model = Model::getModel();
        $nombre = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 10;
        $nombre = max(1, min($nombre, 100));

        $joueurs = $model->selectRandomJoueurs_pred($nombre);

        foreach ($joueurs as $joueur) {
            $model->insertJoueurEnCoursPred($joueur['id_joueur']);
            
        }

        header("Location: ?controller=partie");
        exit();
    }


    public function action_deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_GET['id_joueur'])) {
            $id_joueur = intval($_GET['id_joueur']);
            $model = Model::getModel();
            $model->deleteJoueurs_en_cours($id_joueur);
            header("Location: ?controller=partie");
            exit();
        }
    }

    public function action_editUser()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_joueur']) && !empty($_POST['pseudo']) && !empty($_POST['numbers']) && !empty($_POST['stars']) && isset($_POST['type_joueur'])) {
            $id_joueur = (int)$_POST['id_joueur'];
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));
            $type_joueur = $_POST['type_joueur']; // indique s'il s'agit de Joueurs_pred ou Joueurs_creer
    
            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
    
                if ($type_joueur === 'pred') {
                    $model->updateJoueurPred($id_joueur, $pseudo, $ticket);
                } elseif ($type_joueur === 'creer') {
                    $model->updateJoueurs_creer($id_joueur, $pseudo, $ticket);
                }
    
                header("Location: ?controller=partie");
                exit();
            }
        }
    }
    

    

}

