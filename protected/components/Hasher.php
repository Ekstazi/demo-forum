<?php

namespace app\components;


class Hasher extends Component
{
    protected $salt;

    public function hash($input)
    {
        return password_hash($input, PASSWORD_DEFAULT);
    }

    public function verify($input, $existingHash)
    {
        return password_verify($input, $existingHash);
    }

    protected function getCryptSalt()
    {
        if (isset($this->salt)) {
            return $this->salt;
        }

        $path = App::instance()->getBasePath() . '/runtime/salt';
        if (file_exists($path)) {
            return $this->salt = file_get_contents($path);
        }

        $this->salt = crypt(md5(time()) . rand());
        file_put_contents($path, $this->salt, LOCK_EX);
        return $this->salt;
    }

    public function crypt($string)
    {
        return base64_encode($this->strcode($string));
    }

    protected function strcode($str)
    {
        $salt = $this->getCryptSalt();
        $len = strlen($str);
        $gamma = '';
        $n = $len > 100 ? 8 : 2;
        while (strlen($gamma) < $len) {
            $gamma .= substr(pack('H*', sha1($gamma . $salt)), 0, $n);
        }
        return $str ^ $gamma;
    }

    public function decrypt($string)
    {
        return $this->strcode(base64_decode($string));
    }

    public function getRandom($length = 8)
    {
        return substr(hash('sha512', rand()), 0, $length);
    }
}