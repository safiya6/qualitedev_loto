<?php
session_start();

class Controller_partie extends Controller
{
    public function action_default()
    {
        $model = Model::getModel();
        $joueursEnCours = $model->selectJoueursEnCoursPred();
        $this->render("simulation", ['joueurs' => $joueursEnCours,
        'joueurs_creer' => $joueurs_creer]);
    }

    public function action_selectRandomJoueurs() {
        $model = Model::getModel();
        $nombre = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 10;
        $nombre = max(1, min($nombre, 100));

        $joueurs = $model->selectRandomJoueurs_pred($nombre);

        foreach ($joueurs as $joueur) {
            $model->insertJoueurEnCoursPred($joueur['id_joueur']);
            $model->setChoisiTrue($joueur['id_joueur']);
            
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_joueur']) && !empty($_POST['pseudo']) && !empty($_POST['numbers']) && !empty($_POST['stars'])) {
            $id_joueur = (int)$_POST['id_joueur'];
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));

            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);

                $model = Model::getModel();
                $model->updateJoueurPred($id_joueur, $pseudo, $ticket);

                header("Location: ?controller=partie");
                exit();
            }
        }
    }

    public function action_addSelectedJoueursCreer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_joueurs'])) {
            $model = Model::getModel();

            // Obtenir les joueurs sélectionnés et vérifier qu'ils sont moins de 100 au total
            $count_en_cours = $model->countJoueursEnCours();
            $remaining_slots = 100 - $count_en_cours;

            if ($remaining_slots > 0) {
                $selectedIds = $_POST['selected_joueurs'];
                $nombre_manual = min(count($selectedIds), $remaining_slots);
                $selectedJoueurs = $model->selectSpecificJoueurs_creer(array_slice($selectedIds, 0, $nombre_manual));

                // Marquer les joueurs sélectionnés comme `choisi = true` et ajouter à `joueurs_en_cours`
                foreach ($selectedJoueurs as $joueur) {
                    $model->setChoisiTrue($joueur['id_joueur']);
                    $model->insertJoueurEnCours($joueur['id_joueur'], $joueur['pseudo'], $joueur['ticket']);
                }
            } else {
                $_SESSION['message'] = "La table joueurs_en_cours est pleine (maximum 100 joueurs).";
            }
        }

        header("Location: ?controller=partie");
        exit();
    }
}

