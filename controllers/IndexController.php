<?php

class IndexController extends Controller {

    function __construct($param) {
        parent::__construct($param);
        $this->allowedActions = ['index', 'error'];
    }

    function actionIndex() {
        $params["username"] = 'admin';
        $this->render('index', $params);
    }


}
