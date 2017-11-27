<?php

class site
{
    private $site;
    private $result = [];

    public function __construct(string $site)
    {
        $this->site = $site;
    }

    public function check(bool $https) : array
    {
        $schema = 'http'.($https ? 's' : '');
        $string_http = 'curl -I %s://%s --connect-timeout 3 -A "Check Stats" -X GET 2>/dev/null';
        exec(sprintf($string_http, $schema, $this->site), $data_http);
        $this->result = $data_http;
        return $this->result;
    }

    public function result(bool $https = true) : string
    {
        $this->check($https);
        $string = '<td class="%s"><div class="%s">%s</div></td>';
        if (!!$this->result === false) {
            $td_class = 'no';
            $div_class = 'overlay';
            $content = 'No conectado';
        } else {
            $td_class = 'ok';
            $div_class = 'overlay';
            $content = implode("<br />", $this->result);
            $is_error = !!(!is_array($this->result) || !preg_match('/[23][\d]{2}/', $this->result[0]));
            if ($is_error === true) {
                $td_class = 'warn';
            }
        }
        return sprintf($string, $td_class, $div_class, $content);
    }

    public function header() : string
    {
        return '
        <th class="site_name"><span>'.$this->sitename().'</span><br /><span class="ip">'.$this->dns().'</span></th>';
    }

    public function dns() : string
    {
        return gethostbyname($this->site);
    }

    public function sitename() : string
    {
        return $this->site;
    }
}
