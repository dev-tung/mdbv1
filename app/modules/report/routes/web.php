<?php

Router::get('/admin/reports/revenue', 'ReportController@revenue', ['auth' => 'admin']);
