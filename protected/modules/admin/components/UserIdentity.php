<?class UserIdentity extends CUserIdentity
{
    private $users = array('admin'=>'ApdAm6iSn14', 'a'=>'1');
    private $loginStatus = 0;

    public function authenticate()
    {
        if (!in_array($this->username, array_keys($this->users))){

            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        else if ($this->password!=$this->users[$this->username]){

            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->errorCode = self::ERROR_NONE;
            $this->loginStatus = 1;
        }
        return !$this->errorCode;
    }

    public function getLogin(){
        return $this->login;
    }
}