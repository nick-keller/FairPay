<?php


namespace Ferus\MailBundle\Twig;


class MailRenderExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('blockquote', array($this, 'blockquote'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('decodeMail', array($this, 'decodeMail'), array('is_safe' => array('lol'))),
            new \Twig_SimpleFilter('mailDates', array($this, 'mailDates'), array('is_safe' => array('html'))),
        );
    }

    public function blockquote($string)
    {
        while(preg_match('#\n>#', $string)){
            $string = preg_replace_callback('#(\n>[^\n]*)+#', function($m){
                $content = str_replace("\n>", "\n", $m[0]);
                return '<blockquote style="font-size:14px">'.$content.'</blockquote>';
            }, $string);
        }

        return $string;
    }

    public function decodeMail($string)
    {
        $string = preg_replace('#=\r?\n#', '', $string);
        $string = str_replace('<', '&lt;', $string);
        return urldecode(preg_replace('#=([A-F][0-9])=([A-F][0-9])#', '%$1%$2', $string));
    }

    public function mailDates($string)
    {
        return preg_replace_callback('#Le ([0-9]+ [a-z]+ 20[0-9]{2}) ([0-9]{2}:[0-9]{2}), ([^,]+), ([^&]+) &lt;([^>]+)> a écrit :#', function($m){
            return '<h4 class="bg-info" style="margin:15px 0">'.$m[4].' '.$m[3].' <span style="font-size: 75%;color: #777777;font-weight: normal;line-height: 1;">'.$m[1].', '.$m[2].'</span></h4>';
        }, $string);
    }

    public function getName()
    {
        return 'mail_render_extension';
    }
} 