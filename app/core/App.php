<?php

class App
{
    public function run(): void
    {
        $this->loadHelpers();
        $this->loadRoutes();

        Router::dispatch(
            Request::method(),
            Request::path()
        );
    }

    protected function loadHelpers(): void
    {
        foreach (glob(BASE_PATH . '/app/common/helpers/*.php') as $file) {
            require_once $file;
        }
    }

    protected function loadRoutes(): void
    {
        // web routes modules
        foreach (glob(BASE_PATH . '/app/modules/*/routes/web.php') as $file) {
            require_once $file;
        }

        // api routes modules
        foreach (glob(BASE_PATH . '/app/modules/*/routes/api.php') as $file) {
            require_once $file;
        }
    }
}