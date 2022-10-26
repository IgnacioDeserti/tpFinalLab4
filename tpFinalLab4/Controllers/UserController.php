<?php

namespace Controllers;

use DAO\UserDAO as UserDAO;
use Models\User as User;

class UserController
{
    private $userDAO;
        

    public function __construct()
    {
        $this->userDAO = new UserDAO();            
    }

    public function IndexRegister($message = "")
    {
        require_once(VIEWS_PATH . "registerUser.php");
    }

    public function Login($email, $password)
        {
            $user = $this->userDAO->GetUserByEmail($email);

            if(($user != null) && ($user->getPassword() === $password))
            {
                $_SESSION["loggedUser"] = $user;
                
                require_once(VIEWS_PATH."loginV.php");
            }
            else{
                echo "<script> if(confirm('Verifique que los datos ingresados sean correctos'));";
                echo "window.location = '../index.php';
                </script>"; 
            }                                                          

        } 

        public function Logout()
        {
            if(isset($_SESSION["loggedUser"])){
                session_destroy();
                require_once(VIEWS_PATH."home.php");
            }else{
                require_once(VIEWS_PATH."home.php");
            }
        }
    public function Register($username, $email, $password, $firstName, $lastName, $dateBirth)
    {
        if (!$this->userDAO->isEmailExists($email)) {
            if (!$this->userDAO->isUsernameExists($username)) {

                $user = $this->Add($username, $email, $password, $firstName, $lastName, $dateBirth);
                $_SESSION["loggedUser"] = $user;

                require_once(VIEWS_PATH . "loginV.php");
            } else {
                $this->IndexRegister("The email already exists");
            }
        } else {
            $this->IndexRegister("The email already exists");
        }
    }

    public function Add($username, $email, $password, $firstName, $lastName, $dateBirth)
    {

        $user = new User();
        $user->setId($this->AutoIncrementalID());
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setDateBirth($dateBirth);


        $this->userDAO->Add($user);

        return $user;
    }

    // this logic is from DAO. 
    private function AutoIncrementalID()
    {
        $id = $this->userDAO->getLastid() + 1;
        return $id;
    }

    public function ShowLoginView()
    {
        require_once(VIEWS_PATH . "home.php");
    }

    public function ShowAddView()
    {
        require_once(VIEWS_PATH . "registerUser.php");
    }

    public function ShowListView()
    {
        $userList = $this->userDAO->GetAll();
        require_once(VIEWS_PATH . "listUsers.php");
    }
}
?>