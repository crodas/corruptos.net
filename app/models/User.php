<?php

namespace Model;

/** 
 * @Persist(users) 
 */
class User
{
    /** @Id */
    public $id;

    /** 
     * @Email 
     * @Unique
     */
    public $email;

    /** 
     * @Password 
     */
    public $password;

}
