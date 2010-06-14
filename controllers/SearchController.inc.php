<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

class SearchController implements ControllerCommand
{
	private $userManager;
	private $searchParams;

	public function __construct()
	{
		$this->userManager = new UserManager;
	}

	public function search()
	{
		SecurityController::ensureHasAccess();

		if (!isset($_POST['search']) && !isset($_GET['page']))
		{
			return false;
		}

		/* --- Filter user input --- */
		$searchParams['username'] = isset($_REQUEST['username']) ? strip_tags($_REQUEST['username']) : '';
		$searchParams['firstName'] = isset($_REQUEST['firstName']) ? strip_tags($_REQUEST['firstName']) : '';
		$searchParams['lastName'] = isset($_REQUEST['lastName']) ? strip_tags($_REQUEST['lastName']) : '';
		$searchParams['email'] = isset($_REQUEST['email']) ? strip_tags($_REQUEST['email']) : '';
		$this->searchParams = $searchParams;

		return $this->userManager->searchUsers($searchParams);
	}

	public function getSearchQueryString()
	{
		if (isset($_POST['search']) || isset($_GET['page']))
		{
			return http_build_query($this->searchParams);
		}
		
	        return null;
	}

   	public function execute($action)
   	{
   		$data = false;
		if ($action == 'search')
		{
			$data['users'] = $this->search();
			$data['searchQueryString'] = htmlspecialchars($this->getSearchQueryString());
		}
		
		return $data;
	}
}
