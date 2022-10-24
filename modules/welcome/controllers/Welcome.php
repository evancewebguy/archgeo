<?php
class Welcome extends Trongate {

    /**
     *
     * @todo call functions from another module
    */
 
    function index() {

        $this->module('services');
        $data['services'] = $this->services->homepage_services();

        $this->module('projects');
        $data['projects'] = $this->projects->homepage_projects();

        $this->module('blog_notices');
        $data['articles'] = $this->blog_notices->homepage_blog_notices();

        $this->module('abouts');
        $data['abouts'] = $this->abouts->homepage_abouts();

        $this->module('testimonys');
        $data['testimonies'] = $this->testimonys->homepage_testimonies();

        $this->module('processes');
        $data['processes'] = $this->processes->homepage_processes();

        $this->module('clientlogos');
        $data['clientlogos'] = $this->clientlogos->homepage_clientlogos();

        $this->module('sliders');
        $data['sliders'] = $this->sliders->homepage_sliders();

        $data['headline'] = 'Architectural Design';
        $data['view_module'] = 'welcome';
        $data['view_file'] = 'welcome';
        $this->template('public', $data);
    }

}