<?php

use App\Models\Admin;

beforeEach(function () {

});

test('create admin', function () {
    $admin = new Admin();
    expect($admin)->toBeInstanceOf(Admin::class);
});
