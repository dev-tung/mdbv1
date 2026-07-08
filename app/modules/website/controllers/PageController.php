<?php

class PageController
{
    public function string(): void
    {
        View::render('pages/string');
    }

    public function affiliate(): void
    {
        View::render('pages/affiliate');
    }

    public function career(): void
    {
        View::render('pages/career');
    }

    public function contact(): void {}

    public function warrantyPolicy(): void {}

    public function shippingPolicy(): void {}

    public function returnPolicy(): void {}
}
