<?php
/**
* User Directory
*   Copyright © 2008 Theodore R. Smith <theodore@phpexperts.pro>
* 
* The following code is licensed under a modified BSD License.
* All of the terms and conditions of the BSD License apply with one
* exception:
*
* 1. Every one who has not been a registered student of the "PHPExperts
*    From Beginner To Pro" course (http://www.phpexperts.pro/) is forbidden
*    from modifing this code or using in an another project, either as a
*    deritvative work or stand-alone.
*
* BSD License: http://www.opensource.org/licenses/bsd-license.php
**/
class SearchController implements ControllerCommand
{
	private $userManager;
	private $searchParams;

	/** @var SecurityController **/
	protected $guard;

	public function __construct(SecurityController $guard = null)
	{
		$this->userManager = new UserManager;

		if ($guard === null) { $guard = new SecurityController; }
		$this->guard = $guard;
	}

	public function search()
	{
		$this->guard->ensureHasAccess();

		if (!isset($_POST['search']) && !isset($_GET['page']))
		{
			return false;
		}

		/* --- Get user input --- */
		foreach (array('username', 'firstName', 'lastName', 'email') as $field)
		{
			if (isset($_REQUEST[$field]))
			{
				$searchParams[$field] = $_REQUEST[$field];
			}
		}

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
