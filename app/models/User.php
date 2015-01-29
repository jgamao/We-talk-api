<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;

class User extends Eloquent implements ConfideUserInterface
{
    use ConfideUser;

    public function xmpp()
    {
    	return $this->hasOne('Xmpp', 'user_id');
    }

    public function voip()
    {
    	return $this->hasOne('Voip', 'user_id');
    }

    public function upload()
    {
        return $this->hasOne('Upload', 'user_id');
    }

    public function toArray()
    {
    	$this->load('xmpp', 'voip', 'upload');

		return parent::toArray();    	
    }
}
