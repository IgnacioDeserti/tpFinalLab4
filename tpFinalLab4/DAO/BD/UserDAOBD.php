<?php namespace DAO\BD;

Use Models\User as User;
use \Exception as Exception;
use DAO\BD\Connection as Connection;
use DAO\BD\IUserDAOBD as IUserDAOBD;


class UserDAOBD implements IUserDAOBD{
    
    private $connection;
    private $tableName="user";


    public function Add(User $user){
        try
        {
            $query = "INSERT INTO ".$this->tableName." (username, email,pass,firstName,lastName,dateBirth) VALUES (:username, :email, :pass,:firstName,:lastName,:dateBirth);";
                $parameters["username"] = $user->getUsername();
                $parameters["email"] = $user->getEmail();
                $parameters["pass"] = $user->getPassword();
                $parameters["firstName"] = $user->getFirstName();
                $parameters["lastName"] = $user->getLastName();
                $parameters["dateBirth"] = $user->getDateBirth();

                $this->connection = Connection::GetInstance();
                $this->connection->ExecuteNonQuery($query, $parameters);

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function GetAll(){
        try
            {
                $userList = array();

                $query = "SELECT * FROM ".$this->tableName;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                foreach ($resultSet as $row)
                {                
                    $user = new User();
                    $user->setId($row["userid"]);
                    $user->setUsername($row["username"]);
                    $user->setPassword($row["pass"]);
                    $user->setFirstName($row["firstName"]);
                    $user->setLastName($row["lastName"]);
                    $user->setDateBirth($row["dateBirth"]);

                    array_push($userList, $user);
                }

                return $userList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
    }


    public function GetUserByEmail($email)
        {
            try
            {

                $query = "SELECT * FROM ".$this->tableName." WHERE email = :email";

                $parameters["email"] =  $email;
    
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query,$parameters);

                if(count($resultSet)>0)
                {
                    $resultOneRow=$resultSet[0];
                    
                    $user = new User();
                    $user->setId($resultOneRow["userid"]);
                    $user->setUsername($resultOneRow["username"]);
                    $user->setPassword($resultOneRow["pass"]);
                    $user->setFirstName($resultOneRow["firstName"]);
                    $user->setLastName($resultOneRow["lastName"]);
                    $user->setDateBirth($resultOneRow["dateBirth"]);

                    return $user;                
                }              
                
                
            }
            catch (Exception $ex)
            {
                throw $ex;
            }
        }
    
        public function isEmailExists($email){
            try
            {
                $query = "SELECT count(*) FROM ".$this->tableName." WHERE email = :email";

                $parameters["email"] =  $email;
    
                $this->connection = Connection::GetInstance();
    
                $result = $this->connection->Execute($query,$parameters); //1 if exists, 0 if it doesn't exist.
                $flag=false;
                if ($result==1)
                {
                    $flag=true;
                }
                return $flag;
            }
            catch (Exception $ex)
            {
                throw $ex;
            }
        }

        public function isUsernameExists($username){
            try
            {
                $query = "SELECT count(*) FROM ".$this->tableName." WHERE username = :username";

                $parameters["username"] =  $username;
    
                $this->connection = Connection::GetInstance();
    
                $result = $this->connection->Execute($query,$parameters); //1 if exists, 0 if it doesn't exist.
                
                $flag=false;
                if ($result==1)
                {
                    $flag=true;
                }
                
                return $flag;
            }
            catch (Exception $ex)
            {
                throw $ex;
            }
        }

        public function GetUserByUserId($userid)
        {
            try
            {
                $query = "SELECT * FROM ".$this->tableName." WHERE (userid = :userid)";

                $parameters["userid"] =  $userid;
    
                $this->connection = Connection::GetInstance();
    
                $resultSet = $this->connection->Execute($query,$parameters);
                $resultFirstRow=$resultSet[0];

                $user = new User();
                $user->setId($resultFirstRow["userid"]);
                $user->setUsername($resultFirstRow["username"]);
                $user->setPassword($resultFirstRow["pass"]);
                $user->setFirstName($resultFirstRow["firstName"]);
                $user->setLastName($resultFirstRow["lastName"]);
                $user->setDateBirth($resultFirstRow["dateBirth"]);
                
                return $user;
            }
            catch (Exception $ex)
            {
                throw $ex;
            }
        }
}


?> 