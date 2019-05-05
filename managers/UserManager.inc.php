<?php
/**
* User Directory
*   Copyright (c) 2008, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

interface UserManagerI
{
	public function setUsername($username);
	public function getUserInfo();
	public function createProfile($username, $password, $confirm, $firstName, $lastName, $email);
	public function updateProfile($username, $password, $confirm, $firstName, $lastName, $email);
	public function validatePassword($password);
	public function searchUsers(array $searchParams);
	public function getAllUsers();
}

class UserManager implements UserManagerI
{
	private $userInfo;

	// Status
	const NOT_LOGGED_IN = 100;
	const MISSING_USER_INFO = 101;

	// Successes
	const REGISTERED = 200;
	const CORRECT_PASSWORD = 201;
	const LOGGED_IN = 201;
	const UPDATED_PROFILE = 202;

	// Failures
	const ERROR_BLANK_PASS = 300;
	const ERROR_BLANK_USER = 301;
	const ERROR_BLANK_FNAME = 302;
	const ERROR_BLANK_LNAME = 303;
	const ERROR_BLANK_EMAIL = 304; 
	const ERROR_PASS_MISMATCH = 305;
	const ERROR_USER_EXISTS = 306;
	const ERROR_INCORRECT_PASS = 307;
	
	public function __construct()
    {
        $this->userInfo = new UserInfoStruct;
    }
    
    public function setUsername($username)
    {
        $this->userInfo->username = $username;
    }
    
    /**
     * Returns the private userInfo
     *
     * @return UserInfoStruct
     */
    public function getUserInfo()
    {
        if ($this->userInfo->userID == null)
        {
            if (!isset($_SESSION['userInfo']))
            {
                throw new Exception('_SESSION has no userInfo', self::MISSING_USER_INFO);
            }

            $this->userInfo = $_SESSION['userInfo'];
        }

        return $this->userInfo;
    }

    public function createProfile($username, $password, $confirm, $firstName, $lastName, $email)
    {
		// Make sure data isn't blank
		if ($username == '') { return self::ERROR_BLANK_USER; }
        // 1. Make sure the passwords aren't blank and match.
        if ($password == '')
        {
            return self::ERROR_BLANK_PASS;
        }
        else if ($password != $confirm)
        {
            return self::ERROR_PASS_MISMATCH;
        }
    	
		if ($firstName == '')
		{ 
			return self::ERROR_BLANK_FNAME; 
		}
		if ($lastName == '') 
		{ 
			return self::ERROR_BLANK_LNAME; 
		}
		if ($email == '') 
		{ 
			return self::ERROR_BLANK_EMAIL; 
		}
        
        // 2. Make sure we were given a unique username
        $q1s = 'SELECT COUNT(username) FROM Users WHERE username=?';
        $stmt = queryDB($q1s, array($username));
        $userExists = $stmt->fetchColumn();
        
        if ($userExists == true)
        {
            return self::ERROR_USER_EXISTS;
        }
        
        // 3. We need to use transactions to ensure that a user is fully registered.
        $pdo = getDBHandler();
        $pdo->beginTransaction();

        // 4. Looks good to go; let's insert to the DB.
        // a. Let's salt the password for even better security.
        // NOTE: Storing passwords as plaintext is *evil*.
        $password = substr($password, 0, 2) . $password;
        $q2s = 'INSERT INTO Users (username, password) VALUES (?, PASSWORD(?))';
        // b. The only way this could fail is due to unexpected SQL queries; assume success.
        queryDB($q2s, array($username, $password));
        
        // 5. Insert their profile.
        $userID = $pdo->lastInsertId();
        $q3s = 'INSERT INTO Profiles (userID, firstName, lastName, email) VALUES (' . $userID . ', ?, ?, ?)';
        queryDB($q3s, array($firstName, $lastName, $email));
        
        // If we have gotten this far, it means that we were successful; woohoo!
        // 6. Commit the transaction to the database.
        $pdo->commit();

        $this->userInfo->userID = $userID;
        $this->userInfo->username = $username;
        $this->userInfo->firstName = $firstName;
        $this->userInfo->lastName = $lastName;
        $this->userInfo->email = $email;
        
        return self::REGISTERED;
    }
    
    public function updateProfile($username, $password, $confirm, $firstName, $lastName, $email)
    {
		// Make sure data isn't blank
		if ($username == '') { return self::ERROR_BLANK_USER; }
    	// 1. Make sure the passwords aren't blank and match.
        if ($password == '')
        {
        	return self::ERROR_BLANK_PASS;
        }
        else if ($password != $confirm)
        {
        	return self::ERROR_PASS_MISMATCH;
        }
        
		if ($firstName == '') { return self::ERROR_BLANK_FNAME; }
		if ($lastName == '') { return self::ERROR_BLANK_LNAME; }
		if ($email == '') { return self::ERROR_BLANK_EMAIL; }
        
        // 2. Get the userID.
        $userInfo = $this->getUserInfo();
        $userID = $userInfo->userID;
        
        // 3. We need to use transactions to ensure that a user is fully registered.
        $pdo = getDBHandler();
        $pdo->beginTransaction();
        
        try
        {
            // 4. Let's salt the password for even better security.
            // NOTE: Storing passwords as plaintext is *evil*.
            $password = substr($password, 0, 2) . $password;
            $q1s = 'UPDATE Users SET password=PASSWORD(?) WHERE userID=' . $userID;
            queryDB($q1s, array($password));
            
            $q2s = 'UPDATE Profiles SET firstName=?, lastName=?, email=? WHERE userID=' . $userID;
            queryDB($q2s, array($firstName, $lastName, $email));
        }
        catch(Exception $e)
        {
			// All we can really do is rollback the transaction and rethrow it.
			// @codeCoverageIgnoreStart
			$pdo->rollback();
            
			throw $e;
			// @codeCoverageIgnoreStop
        }
        
        // If we have gotten this far, it means that we were successful; woohoo!
        // 6. Commit the transaction to the database.
        $pdo->commit();

        $this->userInfo->userID = $userID;
        $this->userInfo->username = $username;
        $this->userInfo->firstName = $firstName;
        $this->userInfo->lastName = $lastName;
        $this->userInfo->email = $email;

        return self::UPDATED_PROFILE;
    }
    
    public function validatePassword($password)
    {
        if (is_null($this->userInfo->username))
        {
            throw new Exception('Username was not set prior to password validation.', UserManager::ERROR_BLANK_USER);
        }
        
        $username = $this->userInfo->username;
        // We salted the password for better security.
        // NOTE: Storing passwords as plaintext is *evil*.
        $password = substr($password, 0, 2) . $password;
        $q1s = 'SELECT userID FROM Users WHERE username=? AND password=PASSWORD(?)';
        $stmt = queryDB($q1s, array($username, $password));
        
		if ($stmt === false || ($userID = $stmt->fetchColumn()) === false)
		{
			return self::ERROR_INCORRECT_PASS;
		}
        
        
        // Get profile data.
        $q2s = 'SELECT * FROM vw_UserInfo WHERE userID=?';
        $stmt = queryDB($q2s, array($userID));
        $this->userInfo = $stmt->fetchObject('UserInfoStruct');
        
        return self::CORRECT_PASSWORD;
    }
    
    public function searchUsers(array $searchParams)
    {
        $where = array();
        $params = array();
        foreach ($searchParams as $key => $param)
        {
            if (!empty($param))
            {
                $where[] = $key . ' LIKE ?';
                $params[] = $param;
            }
        }
        
        $q1s = 'SELECT * FROM vw_UserInfo WHERE ' . join($where, ' AND ') . ' ORDER BY username';
        $stmt = queryDB($q1s, $params);
        
        $users = array();
        while (($userInfo = $stmt->fetchObject('UserInfoStruct')))
        {
            $users[] = $userInfo;
        }
        
        return empty($users) ? null : $users;
    }
    
    public function getAllUsers()
    {
        $q1s = 'SELECT * FROM vw_UserInfo ORDER BY username';
        $stmt = queryDB($q1s);
        
        while (($userInfo = $stmt->fetchObject('UserInfoStruct')))
        {
            $users[] = $userInfo;
        }
        
        return $users;
    }
}
