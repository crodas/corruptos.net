<?php

namespace Cli;

/** 
 *  @Cli("user:create") 
 *  @Arg("email", REQUIRED)
 *  @Arg("password", REQUIRED)
 */
function main($input, $output)
{
    $email = $input->getArgument('email');
    $password = $input->getArgument('password');

    $output->writeln("<info>Creating user {$email}</info>");

    $user = new \Model\User;
    $user->email    = $email;
    $user->password = $password;

    \Service::get("db")->save($user);
}
