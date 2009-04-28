<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

require_once 'managers/UserManager.inc.php';

class SearchController
{
    private $userManager;
    private $searchParams;
    
    public function __construct()
    {
        $this->userManager = new UserManager;
    }
    
    public function search()
    {
        if (isset($_POST['search']) || isset($_GET['active']))
        {
            /* --- Filter user input --- */
            $searchParams['username'] = strip_tags($_REQUEST['username']);
            $searchParams['firstName'] = strip_tags($_REQUEST['firstName']);
            $searchParams['lastName'] = strip_tags($_REQUEST['lastName']);
            $searchParams['email'] = strip_tags($_REQUEST['email']);

            $this->searchParams = $searchParams;
            $this->searchParams['active'] = 1;

            return $this->userManager->searchUsers($searchParams);
        }
        
        return null;
    }
}
