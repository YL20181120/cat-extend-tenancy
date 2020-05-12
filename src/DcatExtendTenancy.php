<?php

namespace Yl20181120\DcatExtendTenancy;

use Dcat\Admin\Extension;

class DcatExtendTenancy extends Extension
{
    const NAME = 'dcat-extend-tenancy';

    protected $serviceProvider = DcatExtendTenancyServiceProvider::class;

    protected $composer = __DIR__ . '/../composer.json';

    protected $lang = __DIR__ . '/../resources/lang';

    protected $migrations = __DIR__ . '/../resources/migrations';

    protected $menu = [
        'title' => '站点列表',
        'path' => 'tenants',
        'icon' => 'fa feather icon-circle',
    ];
}
