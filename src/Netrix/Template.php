<?php

namespace Netrix;

class Template {

    private string $base_layout;
    private string $login_component;
    private string $navbar_component;

    public function __construct() {
        $this->base_layout = file_get_contents('./index.html', true);
        $this->login_component = file_get_contents('./login.html', true);
        $this->navbar_component = file_get_contents('./navbar.html', true);
    }

    public function renderLogin() {
        $result = str_replace(
                ['%body%', "%navbar%"],
                [$this->login_component, ""],
                $this->base_layout
        );
        echo $result;
    }

    public function renderMain(string $main) {
        $result = str_replace(
                ['%navbar%','%body%'],
                [$this->navbar_component, $main],
                $this->base_layout
        );

        echo $result;
    }

}
