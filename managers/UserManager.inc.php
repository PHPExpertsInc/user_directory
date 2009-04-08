<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

require_once 'lib/database.inc.php';

// Successes
define('MY_USER_REGISTERED', 200);
define('MY_USER_LOGGED_IN', 201);
define('MY_USER_PROFILE_UPDATED', 202);

// Failures
define('MYE_USER_BLANK_PASS', 203);
define('MYE_USER_PASS_NOMATCH', 204);
define('MYE_USER_EXISTS', 205);
define('MYE_USER_NO_USER', 206);
define('MYE_USER_INVALID_USERPASS', 207);

class UserInfoStruct
{
    public $userID;
    public $username;
    public $firstName;
    public $lastName;
    public $email;
}

class UserManager
{
    private $userInfo;

    public function __construct($username = null)
    {
        $this->userInfo = new UserInfoStruct;
        
        if (!is_null($username))
        {
            $this->userInfo->username = $username;
        }
    }
    
    public function setUsername($username)
    {
        $this->userInfo->username = $username;
    }
    
    public function getUserInfo()
    {
        if ($this->userInfo->userID == null)
        {
            if (!isset($_SESSION['userInfo']))
            {
                throw new Exception('_SESSION has no userInfo');
            }

            $this->userInfo = $_SESSION['userInfo'];
        }

        return $this->userInfo;
    }

    public function createProfile($username, $password, $confirm, $firstName, $lastName, $email)
    {
        // 1. Make sure the passwords aren't blank and match.
        if ($password == '')
        {
            return MYE_USER_BLANK_PASS;
        }
        else if ($password != $confirm)
        {
            return MYE_USER_PASS_NOMATCH;
        }
        
        // 2. Make sure we were given a unique username
        $q1s = 'SELECT COUNT(username) FROM Users WHERE username=?';
        $stmt = queryDB($q1s, array($username));
        $userExists = $stmt->fetchColumn();
        
        if ($userExists == true)
        {
            return MYE_USER_EXISTS;
        }
        
        // 3. We need to use transactions to ensure that a user is fully registered.
        $pdo = getDBHandler();
        $pdo->beginTransaction();

        // 4. Looks good to go; let's insert to the DB.
        try
        {
            // 4.b. Let's salt the password for even better security.
            // NOTE: Storing passwords as plaintext is *evil*.
            $password = substr($password, 0, 2) . $password;
            $q2s = 'INSERT INTO Users (username, password) VALUES (?, PASSWORD(?))';
            // The only way this could fail is due to unexpected SQL queries; assume success.
            queryDB($q2s, array($username, $password));
            
            // 5. Insert their profile.
            $userID = $pdo->lastInsertId();
            $q3s = 'INSERT INTO Profiles (userID, firstName, lastName, email) VALUES (' . $userID . ', ?, ?, ?)';
            queryDB($q3s, array($firstName, $lastName, $email));
        }
        catch(Exception $e)
        {
            // All we can really do is rollback the transaction and rethrow it.
            $pdo->rollback();
            
            throw $e;
        }
        
        // If we have gotten this far, it means that we were successful; woohoo!
        // 6. Commit the transaction to the database.
        $pdo->commit();

        $this->userInfo->userID = $userID;
        $this->userInfo->username = $username;
        $this->userInfo->firstName = $firstName;
        $this->userInfo->lastName = $lastName;
        $this->userInfo->email = $email;
        
        return MY_USER_REGISTERED;
    }
    
    public function updateProfile($username, $password, $confirm, $firstName, $lastName, $email)
    {
        // 1. Make sure the passwords aren't blank and match.
        if ($password == '')
        {
            return MYE_USER_BLANK_PASS;
        }
        else if ($password != $confirm)
        {
            return MYE_USER_PASS_NOMATCH;
        }
        
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
            $pdo->rollback();
            
            throw $e;
        }
        
        // If we have gotten this far, it means that we were successful; woohoo!
        // 6. Commit the transaction to the database.
        $pdo->commit();

        $this->userInfo->userID = $userID;
        $this->userInfo->username = $username;
        $this->userInfo->firstName = $firstName;
        $this->userInfo->lastName = $lastName;
        $this->userInfo->email = $email;

        return MY_USER_PROFILE_UPDATED;
    }
    
    public function validatePassword($password)
    {
        if (is_null($this->userInfo->username))
        {
            throw new Exception('Username was not set prior to password validation.', MYE_USER_NO_USER);
        }
        
        $username = $this->userInfo->username;
        // We salted the password for better security.
        // NOTE: Storing passwords as plaintext is *evil*.
        $password = substr($password, 0, 2) . $password;
        $q1s = 'SELECT userID FROM Users WHERE username=? AND password=PASSWORD(?)';
        $stmt = queryDB($q1s, array($username, $password));
        
        if ($stmt === false)
        {
            return MYE_USER_INVALID_USERPASS;
        }
        
        if (($userID = $stmt->fetchColumn()) === false)
        {
            return MYE_USER_INVALID_USERPASS;
        }
        
        
        // Get profile data.
        $q2s = 'SELECT userID, firstName, lastName, email FROM Profiles WHERE userID=?';
        $stmt = queryDB($q2s, array($userID));
        $this->userInfo = $stmt->fetchObject('UserInfoStruct');
        $this->userInfo->username = $username;
        
        return MY_USER_LOGGED_IN;
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
        
        $q1s = 'SELECT Profiles.userID, username, firstName, lastName, email ' .
               'FROM Profiles JOIN Users USING(userID) ' .
               'WHERE ' . join($where, ' AND ');
        $stmt = queryDB($q1s, $params);
        
        
        $users = array();
        while (($userInfo = $stmt->fetchObject('UserInfoStruct')))
        {
            $users[] = $userInfo;
        }
        
        return $users;
    }
    
    public function getAllUsers()
    {
        $q1s = 'SELECT username, firstName, lastName, email FROM Profiles JOIN Users USING(userID)';
        $stmt = queryDB($q1s);
        
        while (($userInfo = $stmt->fetchObject('UserInfoStruct')))
        {
            $users[] = $userInfo;
        }
        
        return $users;
    }
}