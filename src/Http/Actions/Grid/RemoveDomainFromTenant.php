<?php

namespace Yl20181120\DcatExtendTenancy\Http\Actions\Grid;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RemoveDomainFromTenant extends RowAction
{
    /**
     * @return string
     */
    protected $title = '移除域名';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        $tenant = tenancy()->findByDomain($this->getKey());
        $tenant->removeDomains($this->getKey())
            ->save();
        return $this->response()
            ->success($this->title . ': ' . $this->getKey())
            ->redirect(admin_url('tenants/' . $tenant->id));
    }

    /**
     * @return string|array|void
     */
    public function confirm()
    {
        return [admin_trans('tenant.options.confirm-remove-domain'), $this->getRow()->domain];
    }

    /**
     * @param Model|Authenticatable|HasPermissions|null $user
     *
     * @return bool
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * @return array
     */
    protected function parameters()
    {
        return [];
    }
}
