<?php

namespace Soda\Cms\Database\Observers;

use OwenIt\Auditing\Facades\Auditor;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AuditableObserver
{
    /**
     * Handle the created event for the model.
     *
     * @param \OwenIt\Auditing\Contracts\Auditable $model
     *
     * @return void
     */
    public function created(AuditableContract $model)
    {
        Auditor::execute($model->setAuditEvent('created'));
    }

    /**
     * Handle the updated event for the model.
     *
     * @param \OwenIt\Auditing\Contracts\Auditable $model
     *
     * @return void
     */
    public function saved(AuditableContract $model)
    {
        Auditor::execute($model->setAuditEvent('updated'));
    }

    /**
     * Handle the deleted event for the model.
     *
     * @param \OwenIt\Auditing\Contracts\Auditable $model
     *
     * @return void
     */
    public function deleted(AuditableContract $model)
    {
        Auditor::execute($model->setAuditEvent('deleted'));
    }

    /**
     * Handle the restored event for the model.
     *
     * @param \OwenIt\Auditing\Contracts\Auditable $model
     *
     * @return void
     */
    public function restored(AuditableContract $model)
    {
        Auditor::execute($model->setAuditEvent('restored'));
    }
}
