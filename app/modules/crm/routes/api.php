<?php

// =========================
// CUSTOMER
// =========================
Router::get('/api/customers', 'CustomerEndpoint@apiList');
Router::post('/api/customers', 'CustomerEndpoint@apiCreate');
Router::get('/api/customers/show/{id}', 'CustomerEndpoint@apiShow');
Router::post('/api/customers/update', 'CustomerEndpoint@apiUpdate');
Router::post('/api/customers/delete', 'CustomerEndpoint@apiDelete');
