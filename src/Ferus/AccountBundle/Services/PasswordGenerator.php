<?php


namespace Ferus\AccountBundle\Services;


class PasswordGenerator 
{
    public function generate()
    {
        $odd =  str_split('ZRTPMLKGFXVBN');
        $odd[] = 'CH';
        $odd[] = 'QU';
        $even = str_split('AEYIOU');
        $even[] = 'AI';
        $even[] = 'OI';
        $even[] = 'OU';

        $password = '';
        for($i=0; $i<4; ++$i)
            $password .= $odd[array_rand($odd)].$even[array_rand($even)];
        $password.= rand(10, 99);

        return $password;
    }
} 