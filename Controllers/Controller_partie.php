<?php
session_start();

class Controller_partie extends Controller
{
    public function action_default()
    {
        $model = Model::getModel();
        $joueursEnCours = $model->selectJoueursEnCoursPred();
        $joueurs_creer = $model->selectAllJoueurs_creer();

        // Stocker les joueurs en cours dans la session
        $_SESSION['currentPlayers'] = [];
        foreach ($joueursEnCours as $joueur) {
            $_SESSION['currentPlayers'][$joueur['id_joueur']] = [
                'pseudo' => $joueur['pseudo'],
                'ticket' => $joueur['ticket'],
                'type' => 'pred'
            ];
        }

        $this->render("simulation", ['joueurs' => $joueursEnCours, 'joueurs_creer' => $joueurs_creer]);
    }

    public function action_selectRandomJoueurs()
    {
        $model = Model::getModel();
        $nombre = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 10;
        $nombre = max(1, min($nombre, 100));

        $joueurs = $model->selectRandomJoueurs_pred($nombre);

        foreach ($joueurs as $joueur) {
            $model->insertJoueurEnCoursPred($joueur['id_joueur']);
            $model->setChoisiTrue($joueur['id_joueur']);

            // Ajouter à la session
            $_SESSION['currentPlayers'][$joueur['id_joueur']] = [
                'pseudo' => $joueur['pseudo'],
                'ticket' => $joueur['ticket'],
                'type' => 'pred'
            ];
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

            // Retirer de la session
            if (isset($_SESSION['currentPlayers'][$id_joueur])) {
                unset($_SESSION['currentPlayers'][$id_joueur]);
            }

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
            $type_joueur = $_POST['type_joueur'];

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

                // Mettre à jour la session
                if (isset($_SESSION['currentPlayers'][$id_joueur])) {
                    $_SESSION['currentPlayers'][$id_joueur]['pseudo'] = $pseudo;
                    $_SESSION['currentPlayers'][$id_joueur]['ticket'] = $ticket;
                }

                header("Location: ?controller=partie");
                exit();
            }
        }
    }

    public function action_addSelectedJoueursCreer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_joueurs'])) {
            $model = Model::getModel();
            $selectedIds = $_POST['selected_joueurs'];

            foreach ($selectedIds as $id_joueur_creer) {
                $model->insertJoueurEnCoursFromCreer($id_joueur_creer);

                // Ajouter le joueur à la session
                $joueur_creer = $model->selectJoueurCreerById($id_joueur_creer);
                if ($joueur_creer) {
                    $_SESSION['currentPlayers'][$id_joueur_creer] = [
                        'pseudo' => $joueur_creer['pseudo'],
                        'ticket' => $joueur_creer['ticket'],
                        'type' => 'creer'
                    ];
                }
            }

            $_SESSION['message'] = count($selectedIds) . " joueurs ont été ajoutés à la partie en cours.";
        }

        header("Location: ?controller=partie");
        exit();
    }
}
