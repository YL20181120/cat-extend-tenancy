<?php

namespace Yl20181120\DcatExtendTenancy\Http\Controllers;

use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Traits\HasFormResponse;
use Yl20181120\DcatExtendTenancy\Http\Actions\Grid\RemoveDomainFromTenant;
use Yl20181120\DcatExtendTenancy\Repositories\Domain;
use Yl20181120\DcatExtendTenancy\Repositories\Tenant;

class TenantController extends AdminController
{
    use HasFormResponse;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Tenant(), function (Grid $grid) {
            $grid->model()->latest();
            $grid->id->sortable();
            $grid->name;

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('name');
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Tenant(), function (Show $show) {
            $show->id;
            $show->name;
            $show->domains(function ($model) {
                $grid = new Grid(new Domain);
                $grid->model()->where('tenant_id', $model->id);
                $grid->domain();
                $grid->disableRowSelector();
                $grid->disableEditButton();
                $grid->disableViewButton();
                $grid->disableDeleteButton();
                $grid->actions(function ($actions) {
                    /** @var Grid\Displayers\Actions $actions */
                    $actions->append(new RemoveDomainFromTenant());
                });
                return $grid;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Tenant(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            if ($form->isCreating()) {
                $form->url('domain')->required();
            }
            $form->date('expired_at');
        });
    }

    public function getAddDomain($id, Content $content)
    {
        $form = Form::make(new Tenant(), function (Form $form) {
            $form->display('id');
            $form->display('name');
            if ($form->isEditing()) {
                $form->url('domain')->required();
            }
        })->edit($id);
        return $content
            ->title($this->title())
            ->description(admin_trans('tenant.options.add-domain'))
            ->body($form);
    }
}
