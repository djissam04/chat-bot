<?php
/**
 * class.UserTable.php
 * 
 * ChatBot
 * 
 * @author Mike Buonomo <mrb2590@gmail.com>
 */

class UserTable extends Database
{
    /**
     * Fetches all users
     *
     * @return array $users  An array of User objects
     */
    public function getAllUsers()
    {
        $query = "SELECT * FROM user";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows < 1) {
            throw new Exception('No users found');
        }
        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row['id'], $row['user_agent'], $row['ip_address'], $row['name']);
        }
        return $users;
    }

    /**
     * Fetches a single user by their ID
     *
     * @param User $user
     * @return User  A User object
     */
    public function getUser(User $user)
    {
        $query = "SELECT * FROM user WHERE id='$user->id' LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows !== 1) {
            throw new Exception('No user found');
        }
        $row = $result->fetch_assoc();
        return new User($row['id'], $row['user_agent'], $row['ip_address'], $row['name']);
    }

    /**
     * Fetches a single user by their user agent and IP address
     *
     * @param string $userAgent  User's User Agent
     * @param string $ip  User's IP address
     * @return User  A User object
     */
    public function getUserByDescription($userAgent, $ip)
    {
        $userAgent = $this->mysqli->real_escape_string($userAgent);
        $ip = $this->mysqli->real_escape_string($ip);
        $query = "SELECT * FROM user WHERE user_agent='$userAgent' AND ip_address='$ip' LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result || $result->num_rows !== 1) {
            throw new Exception('No user found');
        }
        $row = $result->fetch_assoc();
        return new User($row['id'], $row['user_agent'], $row['ip_address'], $row['name']);
    }

    /**
     * Creates a new user
     *
     * @param string $userAgent  User Agent of the user, or if bot, version of the bot
     * @param string $ip  IP address of the user or bot
     * @param string $name  Name of the person or bot
     * @return User  User object of the new user created
     */
    public function createUser($userAgent, $ip, $name)
    {
        $userAgent = $this->mysqli->real_escape_string($userAgent);
        $ip = $this->mysqli->real_escape_string($ip);
        $name = $this->mysqli->real_escape_string($name);
        $query = "INSERT INTO user VALUES (NULL, '$userAgent', '$ip', '$name')";
        $result = $this->mysqli->query($query);
        if (!$result) {
            throw new Exception('Could not create user');
        }
        return $this->getUserByDescription($userAgent, $ip);
    }

    /**
     * Updates a user
     *
     * @param User $user  The user to update
     * @return User  User object of the updated user
     */
    public function updateUser(User $user)
    {
        $query = "UPDATE user SET user_agent='$user->userAgent', ip_address='$user->ipAddress', name='$user->name' WHERE id='$user->id'";
        $result = $this->mysqli->query($query);
        if (!$result) {
            throw new Exception('Could not update user');
        }
        return $this->getUser($user);
    }

    /**
     * Deletes a user and all of their conversations and messages from the database
     *
     * @param User $user  The user to update
     */
    public function deleteUser(User $user)
    {
        $query = "DELETE FROM user WHERE id='$user->id'";
        $result = $this->mysqli->query($query);
        if (!$result) {
            throw new Exception('Could not delete user');
        }
    }
}
