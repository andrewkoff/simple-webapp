<?php

class ProfileController extends Controller {
    
    function __construct($param) {
        parent::__construct($param);
        $this->allowedActions = ['index', 'edit', 'add'];
    }
    
    function actionIndex() {
        $params["username"] = 'admin';
        $this->render('index', $params);
    }
    
    function actionEdit() {
        $params = ['username' => 'admin', 'email' => 'admin@admin'];
        $this->render('edit', $params);
    }

    function actionAdd() {
        $db = $this->app->database;
        $new_id = $db->count('user') + 1;
        $this->app->database->insert('user', ['name' => 'user' . $new_id]);

        $params = ['username' => 'admin2', 'email' => 'admin2@admin'];
        $this->render('edit', $params);
    }

}
