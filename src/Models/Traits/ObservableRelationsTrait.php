<?php

namespace Soda\Cms\Models\Traits;

/*
 * http://stackoverflow.com/questions/28925292/eloquent-attach-detach-sync-fires-any-event
 */

trait ObservableRelationsTrait {
    /**
     * Get the observable event names.
     *
     * @return array
     */
    public function getObservableEvents() {
        return array_merge(
            [
                'attaching',
                'detaching',
                'syncing',
            ],
            parent::getObservableEvents()
        );
    }
}
