<?php
session_start();

class Controller_partie extends Controller
{
    public function action_default()
    {
        $this->render("simulation");
    }

    public function action_selectRandomJoueurs() {
        $model = Model::getModel();
        $nombre = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 10;
        $nombre = max(1, min($nombre, 100));

        $joueurs = $model->selectRandomJoueurs_pred($nombre);

        foreach ($joueurs as $joueur) {
            $model->insertJoueurEnCoursPred($joueur['id_joueur']);
        }

        $joueursEnCours = $model->selectJoueursEnCoursPred();
        $this->render("simulation", ['joueurs' => $joueursEnCours]);
    }

    public function action_deleteUser()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_GET['id_joueur'])) {
            $id_joueur = intval($_GET['id_joueur']);
            $model = Model::getModel();
            $model->deleteJoueurs_en_cours($id_joueur);

            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false]);
            exit;
        }
    }


    public function action_editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_joueur'], $_POST['pseudo'], $_POST['numbers'], $_POST['stars'])) {
            $id_joueur = (int)$_POST['id_joueur'];
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));

            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);

                $model = Model::getModel();
                $success = $model->updateJoueurPred($id_joueur, $pseudo, $ticket);

                // Réponse JSON pour AJAX
                echo json_encode(['success' => $success, 'message' => $success ? "Modification réussie" : "Erreur : pseudo ou ticket déjà existant"]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Données non spécifiées ou invalides.']);
        exit;
    }

}
