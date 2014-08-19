<?php

namespace Ferus\UserBundle\Entity;

use Ferus\AccountBundle\Entity\Account;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{

    /**
     * @var Account
     */
    private $account;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @param \Ferus\AccountBundle\Entity\Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return \Ferus\AccountBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}
