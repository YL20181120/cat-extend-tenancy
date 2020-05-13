<?php

namespace Yl20181120\DcatExtendTenancy\Repositories;

use Dcat\Admin\Form;
use Dcat\Admin\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Stancl\Tenancy\Exceptions\TenantDoesNotExistException;
use Stancl\Tenancy\StorageDrivers\RedisStorageDriver;
use Yl20181120\DcatExtendTenancy\Models\Tenant as TenantModel;

class Tenant extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = TenantModel::class;


    public function store(Form $form)
    {
        $domain = parse_url(\request('domain'));
        $tenant = \Stancl\Tenancy\Tenant::create([$domain['host']], [
            'name' => \request('name'),
            'expired_at' => \request('expired_at', null),
            'created_at' => \now(),
            'updated_at' => \now()
        ])->save();
        return $tenant->id;
    }

    public function update(Form $form)
    {
        /* @var EloquentModel $builder */
        $model  = $this->eloquent();
        $tenant = tenancy()->find($form->getKey());
        if (\request()->has('domain')) {
            $domain = parse_url(\request('domain'));
            $tenant->addDomains([$domain['host']])
                ->with('created_at', $model->created_at)
                ->with('updated_at', \now())
                ->save();
        }
        /* @var EloquentModel $builder */
        $model = $this->eloquent();

        if (!$model->getKey()) {
            $model->exists = true;

            $model->setAttribute($model->getKeyName(), $form->getKey());
        }

        $result  = null;
        $updates = $form->updates();
        foreach ($updates as $column => $value) {
            $tenant->with($column, $value);
        }
        $tenant->save();
        return 1;
    }

    public function destroy(Form $form, array $deletingData)
    {
        $tenant = tenancy()->find($form->getKey());
        /** @var RedisStorageDriver $redisDriver */
        $redisDriver = resolve(RedisStorageDriver::class);
        try {
            $redisDriver->findById($form->getKey());
            $redisDriver->deleteTenant($tenant);
        } catch (TenantDoesNotExistException $exception) {

        }
        return parent::destroy($form, $deletingData);
    }
}
