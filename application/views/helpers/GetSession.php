<?php
class Zend_View_Helpers_GetSession {
    public function getSession() {
        $user = new Application_Model_DbTable_User();

        $session = $user->getSession();

        return $session;
    }
}